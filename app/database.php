<?php 

namespace app;

use PDO;

/**
 * class for database hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh yeah */
 
class database 
{
	protected static $pdo; 

	public static function getPdo()
	{
		if(empty(static::$pdo))
		{
			$config = [
				'host' => '127.0.0.1',
				'dbname' => 'tunder25',
				'user' => 'tunder25',
				'password' => 'igOAPNsQ',
			];

			static::$pdo = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=utf8', $config['user'], $config['password']);
		}

		return static::$pdo;
	}

}

?>