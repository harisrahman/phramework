<?php

namespace App\Controllers;

use Core\Framework\Controller;
use App\Models\User;

class HomeController extends Controller
{
	public function index()
	{
		$user = new User;
		var_dump($user->get_data());

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