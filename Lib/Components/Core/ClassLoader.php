<?php

namespace Components\Core;

class ClassLoader
{

    protected $prefixes = array();

    public function register() 
    {
        spl_autoload_register( array($this, 'loadClass') );
    }

    public function addNamespace($prefix, $base_dir, $prepend = false) 
    {
        $prefix = trim($prefix, '\\'). '\\';
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR). '/';    
        if(isset($this->prefixes[$prefix]) === false ) {
            $this->prefixes[$prefix] = array();
        }
           
         // retain the base directory for the namespace prefix   
        if($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);	
        }    
    }

    public function loadClass($class)
	 {
		    $prefix = $class;
		    // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name

        while(false !== $pos = strrpos($prefix, '\\')) {
           // retain the trailing namespace separator in the prefix
           $prefix = substr($class, 0, $pos + 1);
           $relative_class = substr($class, $pos + 1);

           // try to load a mapped file  for the prefix and relative class
           $mapped_file = $this->loadMappedFile($prefix, $relative_class);
           if($mapped_file) {
           	  return $mapped_file;
           }
           // remove the trailing separator for the next iteration
           // of strrpos()
           $prefix = rtrim($prefix, '\\'); // retira espaços em branco do final da string
        }

        // never found a mapped file
        return false;

	}

	protected function loadMappedFile($prefix, $relative_class)
	{ 
        //
        if(isset($this->prefixes[$prefix]) === false) {
           return false;
        }
        /* look throug base directories for this namespace prefix */
        foreach($this->prefixes[$prefix] as $base_dir ) {
           $file = $base_dir
                 . str_replace('\\', '/', $relative_class)
                 . '.php';	
            // if the mapped file exists, require it
           if($this->requireFile($file)) {
              return $file;
           }      
        }

        return  false;

	}
    
    protected function requireFile($file) 
    {
    	if(file_exists($file)) {
    	   require $file;	
    	   return true;
    	}
    	return false;
    }
    
    
}
