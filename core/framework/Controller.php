<?php

namespace Core\Framework;

class Controller
{
	public function not_found()
	{
		return view("errors/404");
	}

}