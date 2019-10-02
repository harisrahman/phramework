<?php

namespace Core\Framework;

class Security
{
	public function generate_csrf_token()
	{
		$token = $_SESSION['csrf_token'] ?? null;

		if (empty($token))
		{
			$_SESSION['csrf_token'] = $token = bin2hex(random_bytes(32));
		}

		return $token;
	}

	public function verify_csrf_token()
	{
		return !empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
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