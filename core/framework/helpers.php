<?php

function request()
{
	$parser = new Core\Routing\UrlParser;

	$coll = collect($parser->get_url_params());
	return $coll->add($parser->get_query_params());
}

function router()
{
	return new Core\Routing\Router();
}

function view_exists(string $view_name)
{
	$view_file = getcwd() . "/app/views/" . $view_name . ".php";

	return file_exists($view_file);
}

function view_or_msg(string $view_name, string $msg = "")
{
	if (view_exists("errors/416"))
	{
		return view("errors/416", ["msg" => $msg]);
	}
	else
	{
		exit($msg);
	}
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

//Returns current datetime for sql
function now()
{
	return date("Y-m-d H:i:s");
}

function xss(string $text)
{
	return (new Core\Framework\Security)->xss($text);
}

function csrf(bool $only_token = false)
{
	return (new Core\Framework\Security)->csrf($only_token);
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