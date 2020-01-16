<?php

namespace Core\Framework;

class Controller
{
	function __construct()
	{
		$security = new Security;

		//Generate security token if it does not exist 
		$security->generate_csrf_token();

		if (strtolower($_SERVER['REQUEST_METHOD']) == "post")
		{
			if ($security->verify_csrf_token() === false)
			{
				return $this->error_419("This page has expired");
			}
		}
	}

	public function error_419(string $msg = "")
	{
		return view_or_exception("errors/419", $msg);
	}

	public function error_404(string $msg = "")
	{
		http_response_code(404);
		return view_or_exception("errors/404", $msg);
	}

	public function error_500(string $msg = "")
	{
		http_response_code(500);
		return view_or_exception("errors/500", $msg);
	}

}
