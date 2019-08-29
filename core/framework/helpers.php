<?php

class Helpers
{
	public $uri, $query;

	public function request()
	{
		$router = new Core\Routing\Router();

		$router->get_matching_route();

		// $this->uri = $parser->parse_url($_SERVER['REQUEST_URI']);
		// $this->query = $parser->parse_query_string($_SERVER["QUERY_STRING"]);

		return $this;
	}

}

function request()
{
	return (new Helpers)->request();	
}

function router($routes)
{
	return new Core\Routing\Router($routes);
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