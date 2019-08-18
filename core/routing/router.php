<?php

namespace Core\Routing;

/**
 * Routes URLs
 */
class Router
{
	private $routes, $uri, $methods, $controller, $action;

	function __construct(array $routes, string $uri)
	{
		$this->routes = $routes;
		$this->uri = $uri;
	}

	private function is_method_defined()
	{
		if (!is_array($this->routes[$this->uri])) return true;

		return array_key_exists(strtolower($_SERVER['REQUEST_METHOD']), $this->routes[$this->uri]);
	}

	private function set_controller_and_action()
	{
		

		$arr = explode("@", $this->routes[$this->uri]);
		var_dump($arr);
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
