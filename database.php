<?php

	$config = require_once 'config.php';
	
	try
	{
		$db = new PDO("mysql:host={$config['host']};dbname={$config['db_name']};charset=utf8", $config['db_user'], $config['db_password'], [
								PDO::ATTR_EMULATE_PREPARES => false,
								PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);
		
		
	}
	catch (PDOException $error)
	{
		echo $error; // na czas fazy developerskiej
		exit('Database error');
	}