<?php

$routes =
[

	"/" => [

		"get" => "index@HomeController",
		"post" => "",

	],

	"/db" => "orm_demo@HomeController",

	"blogs/blog/id={\d+}" => "regex_route@HomeController",

	"/form" => "form@HomeController",

];