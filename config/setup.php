<?php
include_once("database.php");

	try
	{
			$connection = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "CREATE DATABASE IF NOT EXISTS Camagru";
			$connection->exec($sql);
			$sql = "USE Camagru";
			$connection->exec($sql);
			$sql = "CREATE TABLE IF NOT EXISTS users (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				firstname VARCHAR(30) NOT NULL,
				username VARCHAR(30) NOT NULL UNIQUE,
				password VARCHAR(255) NOT NULL,
				email VARCHAR(100) NOT NULL UNIQUE,
				confirmed INT(11) NOT NULL,
				confirm_code INT(11) NOT NULL,
				reg_date TIMESTAMP)";
			$connection->exec($sql);
			$sql = "CREATE TABLE IF NOT EXISTS images (
				id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				image VARCHAR(255) NOT NULL,
				likes INT(6) NOT NULL,
				user_id INT(6) NOT NULL,
				date_created TIMESTAMP)";
			$connection->exec($sql);
			$sql = "CREATE TABLE IF NOT EXISTS comments (
					id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					comment VARCHAR(255) NOT NULL,
					image_id INT(11) NOT NULL,
					user_id INT(6) NOT NULL,
					user_firstname VARCHAR(25) NOT NULL)";
			$connection->exec($sql);
			$sql = "CREATE TABLE IF NOT EXISTS likes (
					id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					user_id INT(11) NOT NULL,
					image_id INT(11) NOT NULL)";
			$connection->exec($sql);
		//	echo "Table/Database created succesfully";
	}
	catch (PDOException $e)
	{
		echo "Conncection failed: ".$e->getMessage()."yep its here";
	}
?>
