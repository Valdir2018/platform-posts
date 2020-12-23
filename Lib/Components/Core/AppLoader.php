<?php


namespace Components\Core;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;

/** 
 * 
 * RecursiveIteratorIterator=> Utilizado para percorrer interatores recursivos. 
 * 
 * 
 **/

class AppLoader 
{
   protected $directories;

   /**
    * Add um doiretório a ser vinculado
    */
    public function addDirectory($diretory) 
    {
        $this->directories[] = $diretory;
    }
    /**
    * Registra o AppLoader 
    **/
    public function register() 
    {
        spl_autoload_register( array($this, 'loadClass') );
    }
    /**
     * Carrega uma classe
     */
    public function loadClass($class) 
	{
       $folders = $this->directories;
       foreach($folders as $folder) 
       {
       	   if(file_exists("{$folder}/{$class}.php")) 
       	   {
              require_once "{$folder}/{$class}.php";
              return TRUE;
       	   }
       	   else 
       	   {  /* Verifica se o arquivo existe  */ 
       	   	  if(file_exists($folder)) 
       	   	  {
                  foreach( new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder),
                                                            RecursiveIteratorIterator::SELF_FIRST) as $entry) 
                  {
                      if(is_dir($entry)) // verifica se é um directory 
                      {
                         if(file_exists("{$entry}/{$class}.php")) 
                         {
                            require_once "{$entry}/{$class}.php";
                            return TRUE;
                         } 
                      } 
                  }
       	   	  }
       	   }
       }
	}
}


