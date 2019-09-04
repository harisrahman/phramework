<?php

namespace App\Models;

use Core\Framework\Model;

class User extends Model
{
	protected $table = 'users';

	public function get_data()
	{
		$this->select(["dsj", "ck"]);
	}


}