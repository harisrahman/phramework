<?php

$routes = [

	"/" => [

		"get" => "index@HomeController",
		"post" => "",

	],

	"/blah" => "blah@HomeController",

	"blogs/blog/\d+" => "index@HomeController",

];