<?php date_default_timezone_set('America/Sao_Paulo');


abstract class RequestServer {
	/**
	* @param LOCK_EX => Bloqueia enquanto estiver escrevendo no arquivo
	* @param FILE_APPEND => Se o arquivo existir apenas acrescente os sem sobrescreve-lo
	*/
	protected static $host;
	public static function requestServerName($path, $session = null) {
		if(is_file($path)){
		   self::$host  = $_SERVER['REMOTE_ADDR']. " ::: ";
		   self::$host .= date('Y-m-d H:i:s'). " ::[USER] ";
		   self::$host .= $session . PHP_EOL;
		   $result = self::$host;
		   file_put_contents($path, $result, FILE_APPEND | LOCK_EX);
		} else {
			throw new FileNotFoundException("Fail: File invalid");
		}
	
	}
}
