<?php

class Helpers
{
	public $uri, $query;

	public function request()
	{
		$parser = new Core\Routing\UrlParser();
		$this->uri = $parser->parse_url($_SERVER['REQUEST_URI']);
		$this->query = $parser->parse_query_string($_SERVER["QUERY_STRING"]);

		return $this;
	}

}

function preg_array_key_match(string $needle, array $haystack)
{
	foreach ($haystack as $key => $value)
	{
		$pattern = "/^" . str_replace('/', '\/', $key) . "$/";
		
		if (preg_match($pattern, $needle))
			return $key;
	}
	return false;
}

function request()
{
	return (new Helpers)->request();	
}

function router($routes, $uri)
{
	return new Core\Routing\Router($routes, $uri);
}

function view(string $view_name, array $data = [])
{
	exit($view_name);
}