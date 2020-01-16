<?php

namespace Core\Routing;

class Route
{
	private function __construct() {}
	public static $curr_route = "";
	public static $curr_method = "";
	public static $routes = [];

	public static function run()
	{
		return new self();
	}

	public static function get(string $name, string $callback)
	{
		self::$curr_route = $name;
		self::$curr_method = "get";
		self::$routes[$name]["get"]["callback"] = $callback;
		return new self();
	}

	public static function post(string $name, string $callback)
	{
		self::$curr_route = $name;
		self::$curr_method = "post";
		self::$routes[$name]["post"]["callback"] = $callback;
		return new self();
	}

	public static function middleware($name)
	{
		self::$routes[self::$curr_route][self::$curr_method]["middleware"] = $name;
		return new self();
	}

}
