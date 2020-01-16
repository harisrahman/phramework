<?php

function request()
{
	$parser = new Core\Routing\UrlParser;

	$coll = collect($parser->get_url_params());
	return $coll->add($parser->get_query_params());
}

function dd()
{
	$args = func_get_args();
	$caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0];

	echo $caller['file'] . " : " . $caller['line'];

	foreach ($args as $arg)
	{
		echo "<pre style='background-color: #1F1A24; color:#d5d5d5; padding: 15px'>";
		var_dump($arg);
		echo "</pre>";
	}
	exit();
}

function router()
{
	return new Core\Routing\Router();
}

function route()
{
	return Core\Routing\Route::run();
}

function view_exists(string $view_name)
{
	$view_file = getcwd() . "/app/views/" . $view_name . ".php";

	return file_exists($view_file);
}

function view_or_exception(string $view_name, string $msg = "")
{
	if (view_exists($view_name))
	{
		return view($view_name, ["msg" => $msg]);
	}
	else
	{
		throw new Exception($msg);
	}
}

function view(string $name, array $data = [])
{
	return Core\Framework\View::compile_view($name, $data);
}

//Returns current datetime for sql
function now()
{
	return date("Y-m-d H:i:s");
}

function xss(string $text) : string 
{
	return (new Core\Framework\Security)->xss($text);
}

function csrf(bool $only_token = false) : string
{
	return (new Core\Framework\Security)->csrf($only_token);
}

function collect($arr = [], bool $is_recursive = false)
{
	return new Core\Framework\Collection($arr, $is_recursive);
}

function extend($name)
{
	return Core\Framework\View::extend_layout($name);
}

function section($name)
{
	return Core\Framework\View::start_section($name);
}

function endsection()
{
	return Core\Framework\View::end_section();
}

function produce($name)
{
	return Core\Framework\View::yield_section($name);
}

function url($path = "")
{
	return (new Core\Routing\UrlParser())->base_url() . $path;
}

function asset($url)
{
	return url() . "/public/" . $url;
}

function redirect($url, $data = null, $statusCode = 303)
{
	if ($url[0] !== "/") $url =  "/" . $url;

	$_SESSION['with_data'] = $data;

	header('Location: ' . url($url), true, $statusCode);
	die();
}

function middleware($name)
{
	$class = "App\Controllers\Middlewares\\" . $name;
	return new $class;
}
