<?php

namespace Core\Framework;

use Exception;

abstract class Model
{
	protected $db;
	protected $query = "";
	protected $wheres = "";
	protected $limit = "";
	protected $params = [];
	protected $withs = [];
	protected $default_date_format = "Y-m-d";

	function __construct()
	{
		$this->db = new Database;
	}

	private function clear()
	{
		$this->query = "";
		$this->wheres = "";
		$this->params = [];
		$this->withs = [];
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
	private function question_marked_params_for_insert(array $record) : string
	{
		return '('. $this->question_markify($record) .')';
	}

/**
 * array to (x=?, y=?, ...)
 */
	private function question_marked_params_for_update(string $column) : string
	{
		return "$column = ?";
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

	public function extract_values_of_key(string $needle, array $arr) : array
	{
		$result = [];

		foreach ($arr as $key => $value)
		{
			if (is_array($value))
			{
				if (array_key_exists($needle, $value)) $result[] = $value[$needle]; 
			}
			elseif($key === $needle)
			{
				$result[] = $value;
			}
		}

		return $result;
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

	private function compile_insert(array $values)
	{
		//Force array to become multi dimension
		if (! is_array(reset($values))) $values = [$values];

		$columns = $this->commafy(array_keys(reset($values)));
		$parameter_placeholders = $this->commafy(array_map([$this, "question_marked_params_for_insert"], $values));
		$actual_parameters = $this->flatten_array($values);

		$this->query = "INSERT INTO $this->table ($columns) VALUES $parameter_placeholders;";
		$this->params = $actual_parameters;
	}

	private function compile_update(array $values)
	{
		$columns = array_keys($values);

		$parameter_placeholders = $this->commafy(array_map([$this, "question_marked_params_for_update"], $columns));

		$this->query = "UPDATE $this->table SET $parameter_placeholders";
		$this->params = array_merge(array_values($values), $this->params);
	}

	private function compile_withs($main_result)
	{
		foreach ($this->withs as $relation)
		{
			if (method_exists($this, $relation))
			{
				$relation_data = $this->{$relation}();

				if (count($relation_data) < 2)
				{
					throw new Exception("Too few arguments in " . $relation . " in " . get_class($this));
				}

				$local_key = count($relation_data) > 2 ? $relation_data[1] : "id";
				$foreign_key = end($relation_data);
				$foreign_class = "\App\Models\\" . $relation_data[0];

				if (class_exists($foreign_class))
				{
					$table_name = (new $foreign_class)->table;
				}
				else
				{
					throw new Exception($relation_data[0] . " class in relation " . $relation . " does not exist");
				}

				$foreign_key_values = $this->extract_values_of_key($local_key, $main_result);

				$result = $this->
							select()			
							->whereIn($foreign_key, $foreign_key_values);

				$this->

				var_dump($result);
				exit();
			}
			else
			{
				throw new Exception("Relation " . $relation . "does not exist for " . get_class($this) . "class");
			}
		}
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
					$data_mod[$key_1][$key_2] = date($this->default_date_format, strtotime($value_2));
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
			$query .= "*";
		}
		else
		{
			$query .= implode(", ", $selects);
		}
		$query .= " FROM " . $this->table;

		return $query;
	}

	public function insert(array $data)
	{
		$this->compile_insert($data);
		$result = $this->db->manipulate_db($this->query, $this->params);

		$this->clear();
		return $result ? $this->db->get_last_id() : false;
	}

	public function update(array $data)
	{
		if ($this->wheres == "") return false;

		$this->compile_update($data);
		$result = $this->db->manipulate_db("$this->query $this->wheres $this->limit;", $this->params);

		$this->clear();
		return $result;
	}

	public function delete() : bool
	{
		if ($this->wheres == "") return false;

		$result = $this->db->manipulate_db("DELETE FROM $this->table $this->wheres $this->limit;", $this->params);

		$this->clear();
		return $result;
	}

	public function select(array $selects = []) : object
	{
		$this->query = $this->compile_select($selects);

		return $this;
	}

	public function where(string $column, string $operator = "=", string $value = null) : object
	{
		if (func_num_args() == 1)
			$value = "NULL";
		elseif (func_num_args() == 2)
			$value = $operator;

		if (func_num_args() < 3) $operator = "=";

		if (trim($this->wheres) == "")
			$this->wheres = " WHERE ";
		else
			$this->wheres .= " AND ";

		$this->wheres .= "$column $operator ?";


		$this->params[] = $value;

		return $this;
	}

	public function whereIn(string $column, array $values) : object
	{
		if (trim($this->wheres) != "")
			$this->wheres = " WHERE";
		else
			$this->wheres .= " AND" ;

		$this->wheres .= " $column IN (" . $this->question_markify($values) . ")";

		$this->params = array_merge($this->params, $values);
		
		return $this;
	}

	public function limit(int $num)
	{
		$this->limit = "LIMIT $num";

		return $this;
	}

	public function raw($query, array $params)
	{
		$this->query .= " " . trim($query);
		$this->params = array_merge($this->params, $params);

		return $this;
	}

	public function with($relations)
	{
		if (! is_array($relations)) $relations = [$relations];

		$this->withs = $relations;

		return $this;
	}

	public function get($reursive_coll = true)
	{
		if($this->query == "") $this->query = "SELECT * FROM  $this->table";

		$result = $this->db->query_db("$this->query $this->wheres $this->limit;", $this->params);

		if (!empty($this->withs))
		{
			$result = $this->compile_withs($result);
		}

		$this->clear();

		return $result;
		return collect($result, $reursive_coll);
	}
}