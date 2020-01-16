<?php

namespace Core\Routing;

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

	public function base_url()
	{
		$exec_script = explode("/", $_SERVER["PHP_SELF"]);

		//Count - 1 because at last is the .php file name
		$url_arr = array_splice($exec_script, 0, count($exec_script) - 1);
		$url_string = implode("/", $url_arr);
		$host_url = sprintf("%s://%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME']);

		return $host_url . $url_string;
	}

	public function parse_url($url)
	{
		$url = urldecode(parse_url($url, PHP_URL_PATH));
		return $this->remove_url_prefix($url);
	}

	public function get_query_params() : array
	{
		parse_str($_SERVER["QUERY_STRING"], $arr);
		return $arr;
	}

	public function get_params_from_route(string $url, string $route) : array
	{
		$params_arr = [];

		if (strpos($route, "={") === false) return $params_arr;

		$url_arr = explode("/", $url);
		$route_arr = explode("/", $route);

		if (count($url_arr) != count($route_arr)) return $params_arr;
		
		foreach ($route_arr as $url_piece_key => $url_piece)
		{
			if (($var_name_end = strpos($url_piece, "={")) !== false)
			{
				$params_arr[substr($url_piece, 0, $var_name_end)] = $url_arr[$url_piece_key];
			}
		}

		return $params_arr;
	}

	public function get_url_params() : array
	{
		$matching_route = (new Router)->get_matching_route();
		
		$curr_url = $this->parse_url($_SERVER['REQUEST_URI']);
		$params_arr = $this->get_params_from_route($curr_url, $matching_route);

		return $params_arr;
	}
}
