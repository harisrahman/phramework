<?php

namespace App\Controllers;

class HomeController extends Controller
{
	public function index()
	{
		return view("homepage", ["name" => "Haris"]);
	}

	public function blah()
	{
		echo "normal route. <br>";
	}

	public function regex_route()
	{
		return view("blog", ["name" => "Blog"]);
	}
}