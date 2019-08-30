<?php

use Core\Routing\UrlParser;
use Core\Routing\Router;

class Helpers
{

	private function set_properties_from_array(array $arr)
	{
		foreach ($arr as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

	private function set_url_params()
	{
		$matching_route = (new Router)->get_matching_route();
		
		$parser = new UrlParser;
		$curr_url = $parser->parse_url($_SERVER['REQUEST_URI']);
		$params_arr = $parser->get_params_from_route($curr_url, $matching_route);

		$this->set_properties_from_array($params_arr);
	}

	private function set_query_params()
	{
		$parser = new Core\Routing\UrlParser;
		$query_arr = $parser->parse_query_string();

		$this->set_properties_from_array($query_arr);
	}

	public function request()
	{
		$this->set_url_params();
		$this->set_query_params();

		return $this;
	}

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