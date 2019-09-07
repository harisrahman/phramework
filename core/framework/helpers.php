<?php

use Core\Routing\UrlParser;
use Core\Routing\Router;

class Helpers
{
	private function get_url_params() : array
	{
		$matching_route = (new Router)->get_matching_route();
		
		$parser = new UrlParser;
		$curr_url = $parser->parse_url($_SERVER['REQUEST_URI']);
		$params_arr = $parser->get_params_from_route($curr_url, $matching_route);

		return $params_arr;
	}

	private function get_query_params() : array
	{
		$parser = new Core\Routing\UrlParser;
		$query_arr = $parser->parse_query_string();

		return $query_arr;
	}

	public function request()
	{
		$coll = collect($this->get_url_params());
		return $coll->add($this->get_query_params());
	}

}

function collect($arr = [], bool $is_recursive = false)
{
	return new Core\Framework\Collection($arr, $is_recursive);

/**
 * Recursive collection example

	$x = collect([ "a" =>
	[
		"c" =>
		[
			"x" => "A",
			"y" => "C"
		],
		"y" =>  "y",
		"x" =>
		[
			"x" => "A",
			"y" => "C"
		],
	],
	"b" => "y" ], true);

	var_dump($x->a->x->x);
 */
}

function request()
{
	return (new Helpers)->request();
}

function router()
{
	return new Core\Routing\Router();
}

function view(string $view_name, array $data = [])
{
	$view_file = getcwd() . "/app/views/" . $view_name . ".php";

	if (file_exists($view_file))
	{
		if (!empty($data)) extract($data);

		require $view_file;
	}
	else
	{
		exit('View "' . $view_name . '" not found.');
	}
}