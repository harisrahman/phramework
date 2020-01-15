<?php

namespace App\Controllers;

use Core\Framework\Controller;
use App\Models\User;

class HomeController extends Controller
{
	public function index()
	{
		return view("index", ["name" => "Human"]);
	}

	public function orm_demo()
	{
		$user_model = new User;

		// $result = $user_model->add("Haris", 24);
		// $result = $user_model->update_by_id(1, ["name" => "John", "age" => 40]);
		// $result = $user_model->delete_by_id(1);

		// $result = $user_model->get_by_name_age("Haris", 24);
		// $result = $user_model->get_by_name_like("Hari");

		$result = $user_model->get_by_name_with_token("Haris");

		return view("index", ["user" => $result->first()]);
	}

	public function regex_route()
	{
		return view("blog", ["name" => "Blog"]);
	}

	public function form()
	{
		return view("form");
	}

}