<?php

use App\Model\Login;
use Components\Page\Page;
use Components\Session\Session;
use Components\Exception\FileNotFoundException;


class LoginForm  {
    private $template;

    /**
    *  Construtor  
    */
    public function __construct() 
    { 
       try {
          if(isset($_REQUEST['method']) == 'login') {
             $this->login(); 
          }
         
       } catch(FileNotFoundException $error) {
            print $error->getMessage();
       }
    }
     
    public function login() 
    {
        $dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($dataForm['logar'])) {   
            unset($dataForm['logar']);
            $userLogin = new Login();
            
            $userLogin->setLogin($dataForm);
            if ($userLogin->getResult()) {
                // Sets sessions
                Session::setValue('logged', TRUE);
                Session::setValue('id_user',    $_REQUEST['id_user']);
                Session::setValue('id_level',   $_REQUEST['id_level']);
                Session::setValue('matricula',  $_REQUEST['matricula']);
                Session::setValue('produto',    $_REQUEST['produto']);
                Session::setValue('nome',       $_REQUEST['nome']);
                Session::setValue('pws_upd',    $_REQUEST['pws_upd']);
                header("Location: index.php?class=ListPost");
                
            } else {
                throw new FileNotFoundException("<p style='text-align:center;background:#ff105f;padding:12px;color:#fff;'>
                    Login ou senha inválida </p>");
            } 
        }
    }
    
    public function logout() 
    {
        if (isset($_REQUEST['action']) == 'action') {
            Session::setValue('nome', FALSE);
            Session::setValue('logged', FALSE);
            Session::setValue('matricula', FALSE);
            header("Location: index.php");
        } 
    }
    public function show() {}

   
}

