<?php

class Helpers
{
	private function set_url_params_as_properties()
	{
		$matching_route = (new Core\Routing\Router)->get_matching_route();
		$params_arr = (new Core\Routing\UrlParser)->get_params_from_route($matching_route);

		// foreach ($params_arr as $key => $value)
		// {
		// 	$this->{$key} = $value;
		// }
	}

	private function set_query_params_as_properties()
	{
		$parser = new Core\Routing\UrlParser;
		$query_arr = $parser->parse_query_string();

		foreach ($query_arr as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

	public function request()
	{
		$this->set_url_params_as_properties();

		$this->set_query_params_as_properties();

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