<?php

namespace App\Middlewares;

class NotAuth
{
	function __construct()
	{
		if (array_key_exists("id", $_SESSION) || ctype_digit($_SESSION["id"]))
		{
			redirect("");
		}
	}
}