<?php

namespace Core\Framework;

use Countable;

class Collection implements Countable
{
	protected $items = [];
	
	function __construct(array $items = [], bool $is_recursive = false)
	{
		if ($is_recursive)
		{

		}
		else
		{
			$this->items = $items;
		}

	}

	public function __set($propName, $value)
	{
		$this->set($propName, $value);		
	}

	public function __get($propName)
	{
		return $this->get($propName);
	}

	public function __toString()
	{
		return $this->to_json();
	}

	private function FunctionName($value='')
	{
		
	}

	private function set($key, $value)
	{
		$this->items[$key] = $value;
	}

	private function get($key)
	{
		return array_key_exists($key, $this->items) ? $this->items[$key] : null;
	}

	public function to_json()
	{
		return json_encode($this->items);
	}

	public function to_array()
	{
		return $this->items;
	}

	public function count()
	{
		return count($this->items);
	}

	public function add($new_items)
	{
		if (is_array($new_items))
			$this->items = array_merge($this->items, $new_items);
		else
			$this->items[] = $new_items;

		return $this;
	}
}