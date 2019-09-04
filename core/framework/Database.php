<?php

namespace Core\Framework;

class Database
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
				\PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //make the default fetch be an associative array
			];
		 	return new \PDO($dsn, getenv("DB_USERNAME"), getenv("DB_PASSWORD"), $options);
		}
		catch(PDOException $e)
		{
			exit("Connection failed: " . $e->getMessage());
		}
	}

/**
 * For insert, update, delete queries
 */
	public function manipulate_db(string $query, array $parameters) : bool
	{
		$stmt = $this->conn()->prepare($query);
		$result = $stmt->execute($parameters);
		$stmt = null;

		return $result;
	}

/**
 * For select queries
 */
	public function query_db($query) : array
	{
		$stmt = $this->conn()->prepare($query);
		$stmt->execute();
		$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}

}