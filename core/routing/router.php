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

	function preg_array_key_exists(string $pattern, array $arr) : bool
	{
		$keys = array_keys($array);    
		return preg_match($pattern, $keys);
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

		$controller = "App\Controllers\\" . $route_string_arr[1];
		$this->controller = new $controller;
		$this->action = $this->controller->{$route_string_arr[0]}();
	}

	public function run()
	{
		if (array_key_exists($this->uri, $this->routes) && $this->is_method_defined())
		{
			$this->set_controller_and_action();
		}
		else
		{
			echo "404";
		}
	}
}
