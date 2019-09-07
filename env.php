<?php

$vars = [

"DB_CONNECTION=mysql",
"DB_HOST=127.0.0.1",
"DB_PORT=3306",
"DB_DATABASE=phramework",
"DB_USERNAME=root",
"DB_PASSWORD=",


];

foreach ($vars as $var)
{
	putenv($var);
}