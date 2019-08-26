<?php

$routes = [

	"/" => [

		"get" => "index@HomeController",
		"post" => "",

	],

	"/blah" => "blah@HomeController",

	"blogs/blog/id={\d+}" => "regex_route@HomeController",

];