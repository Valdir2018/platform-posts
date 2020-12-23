<?php


namespace App\Model;

/**
** @return 
** lista e controla os niveis de acesso do usuário
**  
*/
class UserListOfALL 
{
   private $result;

   public function getResult()  
   {
   	  return $this->result;
   }
   
   /** return all levels  **/
   public function getAccessListUser() 
   {
       $user_list = new \App\Model\Helper\Read();
       $user_list->fullRead("SELECT * FROM tb_level");
       return $this->result = $user_list->getResult();
   }
   /** return user of level operational  **/
   public function getUserOperation() 
   {
       $get_user_level = new \App\Model\Helper\Read();
       $get_user_level->fullRead("SELECT id_level, name_level 
       FROM tb_level WHERE name_level = :level LIMIT :limit", "level=Operador&limit=1");
       $this->result = $get_user_level->getResult();
       return $this->result;
   }
}