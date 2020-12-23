<?php



namespace Components\Session;
/***
 *  Classe responsável por  manipular a sessão do usuário
 * 
 */

class Session 
{
    public static $session;
    public function __construct() 
    {   /** Se não existir um id de sessão */
        if (!session_id()) {
            session_start(); // start new session
        }
    }
   /**
    * Seta uma session
    */
    public static  function setValue($variable, $value) 
    {
        $_SESSION[$variable] = $value;
    }
    /**
     * Retorna uma sessão
     */
    public static function getValue($variable) 
    {
        if (isset($_SESSION[$variable])) {
            self::$session =  $_SESSION[$variable];
        }
        return self::$session;
    }

    public static function freeSession() 
    {
        $_SESSION = array();
        session_destroy();
    }
}