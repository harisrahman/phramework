<?php

$vars = [

"APP_URL_PREFIX=phramework",
"DB_CONNECTION=mysql",
"DB_HOST=127.0.0.1",
"DB_PORT=3306",
"DB_DATABASE=woven_ud",
"DB_USERNAME=root",
"DB_PASSWORD=",


];

foreach ($vars as $var)
{
	putenv($var);
}