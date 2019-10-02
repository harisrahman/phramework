<?php

namespace Core\Framework;

use Core\Framework\Security;

class Controller
{
	function __construct()
	{
//Generate security token if it does not exist 
		(new Security)->generate_csrf_token();

		if (strtolower($_SERVER['REQUEST_METHOD']) == "post")
		{
			if ((new Security)->verify_csrf_token() === false)
			{
				return $this->page_expired("This page has expired");
			}
		}
	}

	public function page_expired(string $msg = "")
	{
		return view_or_msg("errors/419", $msg);
	}

	public function not_found(string $msg = "")
	{
		http_response_code(404);
		return view_or_msg("errors/404", $msg);
	}

	public function error_500(string $msg = "")
	{
		http_response_code(500);
		return view_or_msg("errors/500", $msg);
	}

}