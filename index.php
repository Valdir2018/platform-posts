<?php session_start();

require_once 'Lib/Components/Core/ClassLoader.php';
require_once 'Lib/Components/User/RequestServer.php';

class FileNotFoundException extends Exception {}



$al = new Components\Core\ClassLoader;
$al->addNamespace('Components', 'Lib/Components'); /** Add in namespaces */
$al->register();

/* App loader */
require_once 'Lib/Components/Core/AppLoader.php';
$al = new  Components\Core\AppLoader;
$al->addDirectory('App/Control');          /* Load file Control in directory */
$al->addDirectory('App/Model');            /* Load file Model in directory */
$al->register();


$loader  = require 'vendor/autoload.php';
$loader->register();

use Components\Session\Session;
$usuarios = new App\Model\Login();


$content  = '';
new Session;
/** Se existir uma session logged */
if (Session::getValue('logged')) {
    $template = file_get_contents('App/Templates/home.html');
    if (isset($_GET['pg']) == 'index') {
        $banner   = file_get_contents('App/Templates/banner.html');
    }
    $class = '';
} else {
    $template  = file_get_contents('App/Templates/login.html');
    $class = 'LoginForm'; 
   
}

if (isset($_GET['class']) AND Session::getValue('logged')) {
    $class = $_GET['class'];
}


$path = "logger/logger.txt";
try {
   $nome = Session::getValue('nome');
   RequestServer::requestServerName($path, $nome);
} catch(FileNotFoundException $e) {
   print $e->getMessage();
}


function navigation() {
    $menu = '';
    $object = new App\Control\NavigationItem;
    $data_item_menu = $object->menu();
    $nameuser = Session::getValue("nome");
    $name = mb_strimwidth( $nameuser, 0, 25, "...");

    
    if (isset($data_item_menu)) {
        $menu .= '<span class="material-icons hidden" style="left:13%;position:absolute;top:33px;">account_circle</span><p class="username" id="user">'.$name.'</p> <p style="padding: 10px;"></p>';
        foreach($data_item_menu as $item) {
           $menu .= '<li class="has-subnav">';
              $menu .= '<a href="'.$item['link'].'">';
                $menu .= '<i class="'.$item['menu_icon'].' fa-2x"></i>';
                  $menu .= ' <span class="nav-text">' .  $item['menu_name']  . '</span>';
              $menu .= '</a>';
           $menu .= '</li> ';  

        }

    }
    return $menu;
}


$item_menu = navigation();
$class  = isset($_GET['class'])  ? $_GET['class'] : null;
$method = isset($_GET['method']) ? $_GET['method'] : null;

if(class_exists($class)) {
   try 
   {
        
        $pagina = new $class($_GET);
        if(!empty($method) AND (method_exists($class, $method))) {
           $pagina->$method($_GET);
        }
         ob_start();                   /** Ativa o buffer de saída */
         $pagina->show();
         $content = ob_get_contents(); /** retorna o conteúdo do buffer */
         print $content;
         ob_end_clean();               /** Desativa o buffer de saída na memória */
                                      

   } catch(Exception $error) 
   {
        $content = $error->getMessage() . '<br/>' . $error->getTraceAsString();
   }
} 

// Sessions User
$usr_logged_level = Session::getValue("id_level");

// $username_logg = isset($_SESSION['nome']) ? $_SESSION['nome'] : NULL;
$user_logged  = str_replace('{navigation}',  $item_menu, $template);

// load banner home page
$banner    = isset($banner) ? $banner : NULL; 

$pg_banner = str_replace('{banner}',    $banner,   $template);
$output    = str_replace('{content}',   $content,  $template);
$output    = str_replace('{content}',   $content,  $user_logged);
$output    = str_replace('{class}',     $class,    $output);
print $output;

         