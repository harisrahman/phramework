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

function request()
{
	return (new Helpers)->request();	
}

function router($routes, $uri)
{
	return new Core\Routing\Router($routes, $uri);
}
