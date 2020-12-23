<?php


namespace App\Model;
use Helper;

/**
* @return create new user
* 01/08/2020
*/

class CreateNewUser 
{
   private $result;
   private $data_form;
   private $validate_user;
   private $username;

   public function getUser() 
   {
       return $this->username;
   }

   private function connect() 
   {
      $objeto = new Transaction();
      $this->conn = $objeto->getConn();
      return $this->conn;
   }

   public function saveUsers(array $data) 
   {
   	  $conexao  = $this->connect();
   	  $this->data_form  = $data;
      $validate_data_form  = new \App\Model\Helper\FormValidate();
      $this->result =  $validate_data_form->validateFormData($this->data_form);
      if( !empty($_FILES['arquivo']) ) {
         $data_list_user = new UserCSV();
         $data_list_user->fileUsers($_FILES['arquivo']);
      } 
      else {
           if ( $this->result ) {
                $create_new_user  = $conexao->prepare("
                INSERT INTO tb_users(login_user, name_user, pass_user, id_companies, id_level, id_product, usr_update_pws) 
                VALUES (:login, :nome, :senha, :empresa, :nivel,:produto, :usrupdat)");
                $create_data_password_user_default = password_hash($this->data_form['mat_user'], PASSWORD_DEFAULT);
                $this->data_form['usr_update'] = "NAO";
                $create_new_user->bindParam(':login',   $this->data_form['mat_user']);
                $create_new_user->bindParam(':nome',    $this->data_form['name_user']);
                $create_new_user->bindParam(':senha' ,  $create_data_password_user_default);
                $create_new_user->bindParam(':empresa', $this->data_form['id_companie']);
                $create_new_user->bindParam(':nivel' ,  $this->data_form['id_level']);
                $create_new_user->bindParam(':produto', $this->data_form['id_produto']);
                $create_new_user->bindParam(':usrupdat',$this->data_form['usr_update']);

                if ( !$this->validarUser($this->data_form['mat_user']) ) {
                      $create_new_user->execute();
                      $this->username = true;
                }
           }
      }   	  
   }

   
   private function validarUser(int $value) 
   {   
    	$conexao  = $this->connect();
    	$user = $conexao->query("SELECT login_user FROM tb_users WHERE login_user = $value LIMIT 1");
    	$user->execute();
    	$resultado = $user->fetch();
    	if($resultado >= 1) {
    	   $this->validate_user = true;	
    	}
    	return $this->validate_user;
    }

   public function resetPasswordUser($login_user) 
   {
       // implementation
   }
}