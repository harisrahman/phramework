<?php

namespace Core\Framework;

class Security
{
	public function generate_csrf_token()
	{

		return "token";
	}

	public function csrf(bool $only_token = false)
	{
		$token = $this->generate_csrf_token();

		if ($only_token)
		{
			return $token;
		}
		else
		{
			return '<input type="hidden" name="csrf_token" value="' . $token . '">';
		}
	}

	public function xss(string $text)
	{
		return htmlspecialchars($text);
	}
}