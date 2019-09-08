<?php

namespace App\Models;

use Core\Framework\Model;

class User extends Model
{
	protected $table = 'users';

	public function add($name, $age)
	{
		$user = [
			"name" => $name,
			"age" => $age,
			"created_on" => now(),
		];

		return $this->insert($user);
	}

	public function delete_by_id($id)
	{
		return $this->where("id", $id)
					->delete();
	}

	public function get_by_name_age($name, $age)
	{
		return $this->select(["name", "age"])
					->where("name", "=", $name)
					->where("age", ">", $age)
					->get();
	}

	public function get_by_name_like($name)
	{
		return $this->select()
					->raw("WHERE name LIKE ?", [$name . "%"	])
					->limit(1)
					->get();
	}

	public function update_by_id($id, $data)
	{
		return $this->where("id", $id)
					->update($data);
	}


}