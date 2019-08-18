<?php

class Helpers
{
	public $uri, $query;

	private function remove_uri_prefix()
	{
		if (getenv("APP_URL_PREFIX"))
			$this->uri = str_replace(getenv("APP_URL_PREFIX"), "", $this->uri);

		return $this;
	}

	private function remove_first_slash()
	{
		$this->uri = ltrim($this->uri, "\/");

		return $this;
	}

	private function parse_uri($uri)
	{
		$this->uri = urldecode(parse_url($uri, PHP_URL_PATH));

		return $this->remove_first_slash()->remove_uri_prefix()->uri;
	}

	public function request()
	{
		$this->uri = $this->parse_uri($_SERVER['REQUEST_URI']);
		parse_str($_SERVER["QUERY_STRING"], $this->query);

		return $this;
    }
}

function request()
{
	return (new Helpers)->request();	
}

function router($routes, $uri)
{
	return new \Core\Routing\Router($routes, $uri);
}
