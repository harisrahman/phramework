<?php

namespace Core\Framework;

class Security
{
	public function generate_csrf_token() : string
	{
		if (empty($_SESSION['csrf_token']))
		{
			if (function_exists('random_bytes'))
			{
				$token = bin2hex(random_bytes(32));
			}
			elseif (function_exists('mcrypt_create_iv'))
			{
				$token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
			}
			else
			{
				$token = bin2hex(openssl_random_pseudo_bytes(32));
			}

			$_SESSION['csrf_token'] = $token;
		}

		return $_SESSION['csrf_token'];
	}

	public function verify_csrf_token() : bool
	{
		return !empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
	}

	public function csrf(bool $only_token = false) : string
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

	public function xss(string $text) : string
	{
		return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
	}
}