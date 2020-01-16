<?php

namespace App\Middlewares;

class Auth
{
	function __construct()
	{
		if (!array_key_exists("id", $_SESSION) || !ctype_digit($_SESSION["id"]))
		{
			redirect("login");
		}
	}
}