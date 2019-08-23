<?php

$routes = [

	"/" => [

		"get" => "index@HomeController",
		"post" => "",

	],

	"/blah" => "blah@HomeController",

	"blogs/blog/{\d+}" => "regex_route@HomeController",

];