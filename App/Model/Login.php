<?php


declare(strict_types=1);

namespace App\Model;
use App\Model\Helper\Logger;



class Login 
{
   private $data;
   private $conn;
   private $result;
   public $code_value;

   private $cod_status_login;

   public function getResult() 
   {
       return $this->result;
   }

   public function getCodStatus() 
   {
       return $this->cod_status_login;
   }


  private function connect() 
  {
        $objeto = new Transaction();
        $this->conn = $objeto->getConn();
        return $this->conn;
  }


   public function setLogin(array $data)
   {   
       $this->data = $data;
       $this->validateFormData();
   
       if ($this->result) {
          $login = new \App\Model\Helper\Read();
          $login->fullRead("SELECT id, login_user, name_user, pass_user, id_companies, id_level, id_product, usr_update_pws 
          FROM tb_users  WHERE login_user = :user LIMIT :limit ","user={$this->data['login']}&limit=1");
          $this->result = $login->getResult();
           
          if (!empty($this->result)) {
              $this->checkinPass();
          } else {
              $this->cod_status_login = 0;
              $this->setLogger();
              $this->result = false;
          }
       }
   }
   /**
   * Obtem o status de atualização de senha do user
   */
   public function getValuePassword(int $user_id_login = null) 
   {
        $get_pws_status = new \App\Model\Helper\Read();
        $get_pws_status->fullRead("SELECT id, usr_update_pws FROM tb_users  WHERE id = :user ","user=$user_id_login");
        $this->result = $get_pws_status->getResult();
        if (isset($this->result[0]['usr_update_pws']) == 'NAO') {
            $this->code_value = $this->result[0]['usr_update_pws'];
        }
        return $this->code_value;
          
   }

   public  function itemNavbar($id_user_logged) 
   {
       $usr_list_item_menu = new \App\Model\Helper\Read();
       $usr_list_item_menu->fullRead("
        SELECT DISTINCT  menu.menu_name, menu.link, menu.menu_icon, per.id_menu, per.id_level, per.lib_pub 
        FROM tb_menus AS menu
        INNER JOIN tb_permission 
          AS per ON per.id_menu = menu.id_menu
        LEFT JOIN tb_users 
          AS usr ON usr.id_level = per.id_level
        WHERE per.lib_pub = 1 
          AND usr.id_level = {$id_user_logged} ");
        $this->result = $usr_list_item_menu->getResult();
        return $this->result;
   }

   

   private function checkinPass()
   {             
        if(password_verify($this->data['senha'], $this->result[0]['pass_user']))
        {   
            $cod_status_login = 1;
            $_REQUEST['id_user']    = $this->result[0]['id'];
            $_REQUEST['matricula']  = $this->result[0]['login_user'];
            $_REQUEST['nome']       = $this->result[0]['name_user'];
            $_REQUEST['empresa']    = $this->result[0]['id_companies'];
            $_REQUEST['produto']    = $this->result[0]['id_product'];
            $_REQUEST['id_level']   = $this->result[0]['id_level'];
            $_REQUEST['pws_upd']    = $this->result[0]['usr_update_pws'];
            $this->cod_status_login = 1;
            $this->result = true;

        } 
        else {
            $this->result = false;
        }

         $this->setLogger();
        
   }

   private function validateFormData() 
   {
        $this->data = array_map('strip_tags', $this->data); 
        $this->data = array_map('trim', $this->data);       
        if(in_array('', $this->data))
        {
           $this->result = false;
        } else {
           $this->result = true;        
        }
   } 


   public function setLogger() 
   {   $connection = $this->connect();
       $codigo  =   $this->cod_status_login;
       $this->data['IP'] = Logger::getLogger();
       $form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       if (!empty($form['login'])) {
           $logger_user = $connection->prepare(" INSERT INTO tb_logs_access (user_login, user_ip, date_acess, cod_status_login) 
            VALUES (:login,  :ip, NOW(), :cod)");
           $logger_user->bindParam(':login', $this->data['login']);
           $logger_user->bindParam(':ip',  $this->data['IP']);
           $logger_user->bindParam(':cod', $codigo);
           $logger_user->execute();
       }
   }
        
}

