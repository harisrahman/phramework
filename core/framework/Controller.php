<?php

namespace Core\Framework;

class Controller
{
	function __construct()
	{
		if (strtolower($_SERVER['REQUEST_METHOD']) == "post")
		{
			if ((new Core\Security)->verify_csrf_token() === false)
			{
				return $this->page_expired();
			}
		}
	}

	public function page_expired()
	{
		http_response_code(416);
		return view("errors/416");
	}

	public function not_found()
	{
		http_response_code(404);
		return view("errors/404");
	}

}