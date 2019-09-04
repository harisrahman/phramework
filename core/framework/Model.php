<?php

namespace Core\Framework;

class Model
{
/**
 * Connection to DB
 */
	public function conn()
	{
		try
		{
			$dsn = getenv("DB_CONNECTION") . ":host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_DATABASE");
		 	$options = [
				PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
			];
		 	return new PDO($dsn, getenv("DB_USERNAME"), getenv("DB_PASSWORD"), $options);
		}
		catch(PDOException $e)
		{
			exit("Connection failed: " . $e->getMessage());
		}
	}

/**
 * array to values separated by commas
 */
	private function commafy(array $values) : string
	{
		return implode(', ', $values);
	}

/**
 * array to (blah, blah, ...)
 */
	private function bracketify(array $record) : string
	{
		return '('. $this->commafy($record) .')';
	}

/**
 * Turn array all values to "?" 
 */
	public function question_markify(array $arr) : string
	{
		return $this->commafy(array_fill_keys(array_keys($arr), "?"));
	}

/**
 * array to (?, ?, ...)
 */
	private function question_marked_parameters(array $record) : string
	{
		return '('. $this->question_markify($record) .')';
	}

/**
 * Flattens array form associative to normal (keys as 0, 1 ...)
 */
	public function flatten_array(array $arr) : array
	{
		$return = array();
		array_walk_recursive($arr, function($a) use (&$return) { $return[] = $a; });
		return $return;
	}

	private function escape_characters(array $data) : array
	{
		foreach ($data as $key_1 => $value_1)
		{
			foreach ($value_1 as $key_2 => $value_2)
			{
				$data[$key_1][$key_2] = htmlspecialchars( trim($value_2) );
			}
		}
		return $data;
	}

	private function compile_insert(array $values) : array
	{
//Force array to become multi dimension
		if (! is_array(reset($values))) $values = [$values];

		$columns = $this->commafy(array_keys(reset($values)));
		$parameter_placeholders = $this->commafy(array_map([$this, "question_marked_parameters"], $values));
		$actual_parameters = $this->flatten_array($values);

		return [
				"query" => "INSERT INTO $this->table ($columns) VALUES $parameter_placeholders;",
				"parameters" => $actual_parameters
		];
	}

	public function sql_date_format(mixed $replace_at, array $data) : array
	{
		$data_mod = $data;

		foreach ($data as $key_1 => $value_1)
		{
			foreach ($value_1 as $key_2 => $value_2)
			{
				if ((is_array($replace_at) && in_array($key_2, $replace_at)) || $key_2 == $replace_at)
				{
					$data_mod[$key_1][$key_2] = date("Y-m-d", strtotime($value_2));
				}
			}
		}
		return $data_mod;
	}


	public function insert(array $data) : bool
	{
		$insert = $this->compile_insert($data);

		$stmt = $this->conn()->prepare($insert["query"]);
		$result = $stmt->execute($insert["parameters"]);
		$stmt = null;

		return $result;
	}

	private function compile_select(array $selects = []) : string
	{
		$query = "SELECT ";

		if (empty($selects))
		{
			$query .= "* ";
		}
		else
		{
			$query .= implode(", ", $selects);
		}
		$query .= " FROM " . $this->table . ";";

		return $query;
	}

	public function select(array $selects = []) : bool
	{
		$query = $this->compile_select($selects);

		// var_dump($query);

		$stmt = $this->conn()->prepare($query);
		$result = $stmt->execute();
		$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$stmt = null;

		return $arr;
	}
}