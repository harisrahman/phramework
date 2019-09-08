<?php

namespace Core\Framework;

use Countable;

class Collection implements Countable
{
	protected $items = [];
	
	function __construct(array $items = [], bool $is_recursive = false)
	{
		$this->items = $items;

		if ($is_recursive)
			$this->items = $this->set_recursive($this)->items;
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

	private function set_recursive(Collection $coll) : object
	{
		foreach ($coll->items as $key => $value)
		{
			if (is_array($value))
				$coll->items[$key] = $coll->set_recursive(new Collection($value));
			else
				$coll->items[$key] = $value;
		}
		return $coll;
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

	public function first()
	{
		return reset($this->items);
	}

	public function last()
	{
		return end($this->items);
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