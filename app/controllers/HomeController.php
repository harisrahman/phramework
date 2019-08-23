<?php

namespace App\Controllers;

class HomeController extends Controller
{
	public function index()
	{
		echo "Index. <br>";
	}

	public function blah()
	{
		echo "normal route. <br>";
	}

	public function regex_route()
	{
		echo "regex route. <br>";
	}
}