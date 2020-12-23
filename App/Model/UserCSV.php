<?php




namespace App\Model;
use Exception;
use Components\File\IOFile;
use Components\Exception\FileNotFoundException;
/**
* Class responsável por efetuar cadastro em massa
*
*
*/

class UserCSV 
{ 
  private $data_file;
	private $result;

	public function gerResult() 
	{
		return $this->result;
	}

  /** Obtém a conexao  **/
  private function connect() 
  {
      $objeto = new Transaction();
      $this->conn = $objeto->getConn();
      return $this->conn;
  }
  /**
  * Recebe o arquivo, percorre o  núm de rows
  * Armazena em data
  */
    
	public function fileUsers(array $dados) 
    {
      $this->data_file = $dados;

      if(!empty($this->data_file)) {
          if(is_uploaded_file($_FILES['arquivo']['tmp_name'])) {
            $handle = fopen($_FILES['arquivo']['tmp_name'], "r");
            $row = TRUE;
            while(($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
              if($row) {
                 $row = FALSE; 
                  continue;    
              }
              $this->sql($data);
                            
            } fclose($handle);
         } 
      }

  }

  private function sql($data) 
  {
      $conexao  = $this->connect();
      $this->data_file = $data;
      try {
           $create_new_user  = $conexao->prepare("
           INSERT INTO tb_users(login_user, name_user, pass_user, id_companies, id_level, id_product) 
           VALUES (:login, :nome, :senha, :empresa, :nivel,:produto)");
           $create_data_password_user_default = password_hash($this->data_file[0], PASSWORD_DEFAULT);
           $create_new_user->bindParam(':login',   $this->data_file[0]);
           $create_new_user->bindParam(':nome',    $this->data_file[1]);
           $create_new_user->bindParam(':senha' ,  $create_data_password_user_default);
           $create_new_user->bindParam(':empresa', $this->data_file[3]);
           $create_new_user->bindParam(':nivel' ,  $this->data_file[4]);
           $create_new_user->bindParam(':produto', $this->data_file[5]);
              
           if($create_new_user->execute()) {
              $this->result  = TRUE;
           } 
       } catch(Exception $e) {
            $this->result = FALSE;
            die("<b> Houve um erro interno !</b> Usuário (s) já existente na base de dados. ".IOFile::backPage());
          
      }
   }
		

}