<?php

namespace Core\Routing;

/**
 * Routes URLs
 */
class Router
{
	private $routes, $uri, $method, $route_string, $controller, $action;

	function __construct(array $routes, string $uri)
	{
		$this->routes = $this->remove_first_slashes($routes);
		$this->uri = $uri;
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

	private function route_match(string $needle, array $haystack)
	{
		foreach ($haystack as $key => $value)
		{
			if (strpos($key, "{") === false || strpos($key, "}") === false) continue;

			$pattern = "/^" . str_replace(['/', '{', '}'], ['\/', '', ''], $key) . "$/";

			if (preg_match($pattern, $needle))
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
		$this->controller = new $controller;
		$this->action = $this->controller->{$route_string_arr[0]}();
	}

	public function run()
	{
//First check if exact route exists
		if (array_key_exists($this->uri, $this->routes))
		{
			if ($this->is_method_defined())
			{
				return $this->set_controller_and_action();
			}
		}
//Then match if regex route exists
		elseif ($this->uri = $this->route_match($this->uri, $this->routes))
		{
			if ($this->is_method_defined())
			{
				return $this->set_controller_and_action();
			}
		}
//Return 404
		return (new \Core\Framework\Controller)->not_found();
	}
}
