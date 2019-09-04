<?php

namespace Core\Framework;

class Model
{
	protected $db;

	function __construct()
	{
		$this->db = new Database;
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

	public function insert(array $data) : bool
	{
		$insert = $this->compile_insert($data);

		return $this->db->manipulate_db($insert["query"], $insert["parameters"]);
	}

	public function select(array $selects = []) : array
	{
		$query = $this->compile_select($selects);
		$result = $this->db->query_db($query);

		return $result;
	}
}