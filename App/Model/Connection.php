<?php

namespace App\Model;
use PDOException;
use PDO;
require "Config.php";

abstract class Connection 
{
    
    public static $host = HOST;
	public static $user = USER;
	public static $pass = PASS;
	public static $database = DBNAME;
	private static $connect = null;

	private static function conexao() {
        try
        {
			if(self::$connect == null){
               self::$connect = new PDO("mysql:host=". self::$host . ";charset=utf8;dbname=" . self::$database, self::$user, self::$pass);
			}
		} catch(PDOEXCEPTION $e){
           die('<h3 style="background: pink; padding: 12px;"> An error occurred while trying to connect to the database ! </h3>'. $e->getMessage(). ' - Cod: '.$e->getCode());
		} 
		self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return self::$connect;
	}

    public function getConnect() 
    {
        return self::conexao();
	}
}