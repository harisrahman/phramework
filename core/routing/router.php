<?php

namespace Core\Routing;

use Core\Framework\Controller as BaseController;

/**
 * Routes URLs
 */
class Router
{
	private $routes, $uri, $method, $route_string, $controller, $action;

	function __construct()
	{
		require(__DIR__. '/../../routes/web.php');
		$this->routes = $this->remove_first_slashes($routes);
	}

	private function remove_first_slashes(array $routes) : array
	{
		foreach ($routes as $key => $value)
		{
			$new_key = ltrim($key, "\/");
			$new_key = $new_key == "" ? "/" : $new_key; 
			$arr[$new_key]  = $value;
		}
		return $arr;
	}

	private function generate_regex(string $subject) : string
	{
		$uri_arr = explode("/", $subject);

		foreach ($uri_arr as $key => $value)
		{
			if (($regex_start = strpos($value, "{")) !== false && ($regex_end = strrpos($value, "}")) !== false)
			{
				$uri_arr[$key] = substr($value, $regex_start + 1, ($regex_end - $regex_start - 1));
			}
		}

		return "/^" . implode("\/", $uri_arr) . "$/";
	}


	private function route_match(string $needle, array $haystack)
	{
		foreach ($haystack as $key => $value)
		{
			if (strpos($key, "{") === false || strpos($key, "}") === false) continue;

			if (preg_match($this->generate_regex($key), $needle))
				return $key;
		}
		return false;
	}

	private function is_method_defined()
	{
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);

		if (!is_array($this->routes[$this->uri]))
		{
			$this->route_string = $this->routes[$this->uri];
			return true;
		}
		elseif (array_key_exists($this->method, $this->routes[$this->uri]))
		{
			$this->route_string = $this->routes[$this->uri][$this->method];
			return true;
		}

		return false;
	}

	private function set_controller_and_action()
	{
		$route_string_arr = explode("@", $this->route_string);

		$controller = "\App\Controllers\\" . $route_string_arr[1];
		
		if (class_exists($controller))
		{
			$this->controller = new $controller;
		}
		else
		{
			(new BaseController)->error_500("Route controller " . $route_string_arr[1] ." not found.");
		}

		if (method_exists($this->controller , $route_string_arr[0])) 
		{
			$this->action = $this->controller->{$route_string_arr[0]}();
		}
		else
		{
			(new BaseController)->error_500("Route action " . $route_string_arr[0] . " not found in " . $route_string_arr[1] . " class.");
		}
	}

	public function get_matching_route()
	{
		$parser = new UrlParser();

		$this->uri = $parser->parse_url($_SERVER['REQUEST_URI']);

//First check if exact route exists
//Then match if regex route exists
		if ((array_key_exists($this->uri, $this->routes) ||
			$this->uri = $this->route_match($this->uri, $this->routes))
			&& $this->is_method_defined())
		{
			return $this->uri;
		}
		return false;
	}

	public function run()
	{
		if ($this->get_matching_route() !== false)
		{
			return $this->set_controller_and_action();
		}
//Return 404
		return (new \Core\Framework\Controller)->error_404();
	}
}
