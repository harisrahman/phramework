<?php

namespace Core\Routing;

/**
 * Routes URLs
 */
class UrlParser
{
	private function remove_url_prefix($url)
	{
		$curr_url = explode("/", $url);
		$exec_script = explode("/", $_SERVER["PHP_SELF"]);

//Count - 1 because at last is the .php file name
		$url_arr = array_splice($curr_url, count($exec_script) - 1);
		$url_string = implode("/", $url_arr);
		$url_string = $url_string == "" ? "/" : $url_string;
		
		return $url_string;
	}

	public function parse_url($url)
	{
		$url = urldecode(parse_url($url, PHP_URL_PATH));
		return $this->remove_url_prefix($url);
	}

	public function parse_query_string()
	{
		parse_str($_SERVER["QUERY_STRING"], $arr);
		return $arr;
	}

	public function get_params_from_route($route)
	{
		if (strpos($key, "{") === false)
		{
			
		}

		var_dump($route);
	}

}
