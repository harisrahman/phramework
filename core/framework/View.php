<?php

namespace Core\Framework;

use Exception;

class View
{
	public static $layout = "";
	public static $sections = [];
	public static $rendering_section = "";
	public static $view_file = "";

	public static function start_section($name)
	{
		self::$rendering_section = $name;
		ob_start();
	}

	public static function end_section()
	{
		self::$sections[self::$rendering_section] = ob_get_clean();
		self::$rendering_section = "";
	}

	public static function extend_layout($name)
	{
		$file = getcwd() . "/app/views/layouts/" . $name . ".php";

		if (file_exists($file))
		{
			ob_start();
			require $file;
			self::$layout = ob_get_clean();
		}
		else
		{
			throw new Exception("Layout " . $name . " in " . $file . " not found.");
		}
	}

	public static function compile_view($view_name, array $data)
	{
		$view_file = getcwd() . "/app/views/" . $view_name . ".php";

		if (file_exists($view_file))
		{
			if (!empty($data)) extract($data);
			if (array_key_exists('with_data', $_SESSION) && is_array($_SESSION['with_data']))
			{
				extract($_SESSION['with_data']);
				unset($_SESSION['with_data']);
			}

			$old = collect();

			if (array_key_exists('old_data', $_SESSION) && is_array($_SESSION['old_data']))
			{
				$old = collect($_SESSION['old_data']);
				unset($_SESSION['old_data']);	
			}

			require $view_file;

			self::$view_file = $view_file;

			self::yield_view();
		}
		else
		{
			throw new Exception('View "' . $view_name . '" not found.');
		}
	}

	public static function yield_view()
	{
		echo self::$layout;
	}

	public function yield_section($name)
	{
		if (array_key_exists($name, self::$sections))
		{
			echo self::$sections[$name];
		}
		else
		{
			throw new Exception('Section ' . $name . ' not found in ' . self::$view_file . '.');
		}

	}
}