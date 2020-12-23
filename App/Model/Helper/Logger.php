<?php



namespace App\Model\Helper;



date_default_timezone_set('America/Sao_Paulo');


abstract class Logger {

	
	
	public static function getLogger() 
	{   
		$user_ip  = $_SERVER['REMOTE_ADDR'];
		$content  = $user_ip;
		return $content;
	}
}
