<?php



namespace App\Model;
use Components\File\IOFile;
use Components\Session\Session;
use Components\Exception\FileNotFoundException;


class PostSave {
	private $conn;
	private $result;
	private $formData;
	private $filename;

	private $directory;

	/**
	* @return formData armazena o valor do formulário
	*/

	/** GETTERS **/
	public function getResult() 
	{
		return $this->result;
	}

	private function connect() 
	{
        $objeto = new Transaction();
        $this->conn = $objeto->getConn();
        return $this->conn;
    }

	public function savePostForm( array $data ) 
	{   $conexao = $this->connect();
		$this->formData = $data;
        $action_validate = new \App\Model\Helper\FormValidate;
        $this->result = $action_validate->validateFormData($this->formData);
        if ($this->result) {
            $create_postagem = $conexao->prepare("INSERT INTO tb_posts(titulo_post, id_type_post, id_product, path_post, comments, date_post, p_status) VALUES (:title, :type,:id_prod,  :arquivo, :comment,  CURRENT_TIMESTAMP(), :status_post)");
			$filename = $this->__uploadFile($this->formData); 
			$arquivo = isset($filename) ? $filename : '';
			$status = '1';
			$this->formData['comment'] =  $this->formData['comment'] == '-' ?  $this->formData['comment'] = '' :  $this->formData['comment'];
			$create_postagem->bindParam(':title' ,    $this->formData['titulo']);
			$create_postagem->bindParam(':type' ,     $this->formData['type_post']);
			$create_postagem->bindParam('id_prod' ,   $this->formData['produto_id']);
			$create_postagem->bindParam('comment' ,   $this->formData['comment']);
			$create_postagem->bindParam(':arquivo',   $arquivo);
			$create_postagem->bindParam(':status_post',   $status);
			$create_postagem->execute();
        }

	}

	public function addFormQuestion(int $id_post, $questao) 
	{   
	    $questao = str_replace("_", " ", $questao);
		$conexao = $this->connect();
		if (!empty($id_post)) {
			$add_form = $conexao->prepare(" INSERT INTO tb_question (id_post, description_question) VALUES (:id_post,  :questao)");
			$add_form->bindParam(':id_post', $id_post);
			$add_form->bindParam(':questao', $questao);
			$add_form->execute();
		}

	}

	public function addFormAlternative(int $id_question, $alternative, $add_field = 0) 
	{   
		$alternative = str_replace("_", " ",  $alternative);
		$conexao = $this->connect();
		// Faz update na tabela de qustões, habilita um campo p/ resposta
	    $add_field  =  $conexao->prepare(" UPDATE tb_question SET text_input = '$add_field' WHERE id = '$id_question' ");
		
		$add_alternative = $conexao->prepare(" INSERT INTO tb_answers (id_question, description_answers) VALUES (:id,
	    :alternative)");
	    
	    $add_alternative->bindParam(':id', $id_question);
	    $add_alternative->bindParam(':alternative', $alternative);
	    
	    $add_alternative->execute();
	    $add_field->execute();
	}
	public function saveAddComent( string $comment, int $iduser, int $idpost) 
	{   $conexao = $this->connect();
		$comment = str_replace("_", " ",   $comment);
		if ( !empty($comment) ) {
			$add_comment_post = $conexao->prepare(' UPDATE  tb_questions_recorded SET comment = :comment  WHERE id_users = :userid AND id_post = :postid ');
	        $add_comment_post->bindParam(':comment',  $comment);
	        $add_comment_post->bindParam(':userid',  $iduser);
	        $add_comment_post->bindParam(':postid', $idpost);
	        $add_comment_post->execute();
		}

	}


	public function addFieldComment(int $userid, int $postid, int $questionid,  string $text ) 
	{   $conexao = $this->connect();
		if ( !empty($text) ) {
			$add_comment_post = $conexao->prepare(' UPDATE  tb_questions_recorded SET commet_justify = :comment  
			WHERE id_users = :userid AND id_post = :postid AND id_question = :question_id ');

	        $add_comment_post->bindParam(':userid',  $userid);
	        $add_comment_post->bindParam(':postid',  $postid);
	        $add_comment_post->bindParam(':question_id',  $questionid);
	        $add_comment_post->bindParam(':comment', $text);
	        $add_comment_post->execute();
		}
	}

	public function get(int $id_usr, int $id_post) 
	{
        $connect = $this->connect();
        $get_id  = $connect->prepare('SELECT id_usr, id_post FROM tb_liked WHERE id_usr = :id_usr AND id_post = :id_post');
        $get_id->bindParam(':id_usr', $id_usr);
        $get_id->bindParam(':id_post', $id_post);
        $get_id->execute();
        $count = $get_id->rowCount();

        return $count;
	}

	// save liked do usr
	public function saveLiked( array $data_liked ) 
	{   
        $conexao = $this->connect();
        $rows = $this->get($data_liked['userid'], $data_liked['postid']);
        if ($rows === 0) {
        	$add_liked_post = $conexao->prepare(' INSERT INTO tb_liked (id_usr, id_post, liked) VALUES (:userid, :postid, :liked)');
            $add_liked_post->bindParam(':userid', $data_liked['userid']);
            $add_liked_post->bindParam(':postid', $data_liked['postid']);
            $add_liked_post->bindParam(':liked' , $data_liked['liked']);
            $add_liked_post->execute();
        } else {
            $add_desliked_post = $conexao->prepare('UPDATE tb_liked SET liked = :liked, desliked = :desliked WHERE id_usr = :userid AND id_post = :postid');
	        $data_desliked = $data_liked;
	        $data_desliked['desliked'] = '';
	        $add_desliked_post->bindParam(':liked',    $data_desliked['liked']);
	        $add_desliked_post->bindParam(':userid',   $data_desliked['userid']);
	        $add_desliked_post->bindParam(':postid' ,  $data_desliked['postid']);
	        $add_desliked_post->bindParam(':desliked', $data_desliked['desliked']);
	        $add_desliked_post->execute();	

        }
       
	}
    // update
	public function saveDesliked( array $data_desliked ) 
	{
        $conexao = $this->connect();
       
        $rows = $this->get($data_desliked['userid'], $data_desliked['postid']);
        if($rows > 0) {
           $add_desliked_post = $conexao->prepare('UPDATE tb_liked SET liked = :liked, desliked = :desliked WHERE id_usr = :userid AND id_post = :postid');
	       $data_desliked['liked'] = '';
	       $add_desliked_post->bindParam(':liked',    $data_desliked['liked']);
	       $add_desliked_post->bindParam(':userid',   $data_desliked['userid']);
	       $add_desliked_post->bindParam(':postid' ,  $data_desliked['postid']);
	       $add_desliked_post->bindParam(':desliked', $data_desliked['desliked']);
	       $add_desliked_post->execute();

        } 
        else {
          $data_desliked['desliked'] = 'desliked';	
          $add_liked_post = $conexao->prepare(' INSERT INTO tb_liked (id_usr, id_post, desliked) VALUES (:userid, :postid, :desliked)');
          $add_liked_post->bindParam(':userid',  $data_desliked['userid']);
          $add_liked_post->bindParam(':postid',  $data_desliked['postid']);
          $add_liked_post->bindParam(':desliked' ,  $data_desliked['desliked']);
          $add_liked_post->execute();
        }
	}

	public function updatePassword( array $data_form ) 
	{
        $conexao = $this->connect();
        $this->formData = $data_form;
        $this->formData['pws_update'] = "SIM";
        $cod_session_usr_logged = Session::getValue('id_user');
        $password_hash = password_hash($this->formData['novasenha'], PASSWORD_DEFAULT);
        $form_validate = new \App\Model\Helper\FormValidate; 
        $form_validate->validateFormData($this->formData);

        if ($form_validate->getResult() ) 
        {
        	$password_update = $conexao->prepare(" UPDATE tb_users SET pass_user = :password, usr_update_pws = :status 
        	WHERE id = :id_usr ");
        	$password_update->bindParam(':password', $password_hash);
        	$password_update->bindParam(':status',   $this->formData['pws_update']);
        	$password_update->bindParam(':id_usr',   $cod_session_usr_logged);
        	$password_update->execute();
        }
	}


	public function resetPassword( $user_id ) 
	{
	    $conexao = $this->connect();
        $password_new = "12345";
        $password_status = "NAO"; 
        $password_hash = password_hash($password_new, PASSWORD_DEFAULT);
      
        if ( !empty($password_hash) ) 
        {
        	$password_update = $conexao->prepare(" UPDATE tb_users SET pass_user = :password, usr_update_pws = :status 
        	WHERE id = :id_usr ");
        	$password_update->bindParam(':password', $password_hash);
        	$password_update->bindParam(':status',   $password_status);
        	$password_update->bindParam(':id_usr',   $user_id);
        	$password_update->execute();
        }	
	}

	/**
	 *
	 * @var recebe um array de dados , trata da persistência dos dados no BD
	 * 
	 **/
	public function saveAnwers(array $data_form ) 
	{
        $conexao = $this->connect();
        $this->formData  = $data_form;
        $action_validate = new \App\Model\Helper\ActionValidate;
        // caso exista respostas, apenas atualize   
		if( $action_validate->isValidate($this->formData['sessao_user_id'], $this->formData['id_question']) ) {
	        $form_update = $conexao->prepare("UPDATE tb_questions_recorded 
	        SET id_answers = :resposta, id_post = :post WHERE id_question = {$this->formData['id_question']}");
	        $form_update->bindParam(':resposta', $this->formData['element_value']);
	        $form_update->bindParam(':post', $this->formData['postagem_id']);
		    $form_update->execute();
			
		} 
		else {
			$form_insert_resp = $conexao->prepare("INSERT INTO tb_questions_recorded(id, id_users, id_post, id_question, id_answers) VALUES (NULL, :user, :post, :question, :id_resposta)");
	        $form_insert_resp->bindParam(':user', $this->formData['sessao_user_id']);
	        $form_insert_resp->bindParam(':post', $this->formData['postagem_id']);
	        $form_insert_resp->bindParam(':question', $this->formData['id_question']);
	        $form_insert_resp->bindParam(':id_resposta', $this->formData['element_value']);
	        $form_insert_resp->execute();

		}
		
	}
 
	public function postEdit( array $data ) 
	{
        $conexao = $this->connect();
        $this->formData = $data;
        $post_edit = $conexao->prepare(" UPDATE tb_posts 
        	SET titulo_post = :titulo, 
        	id_type_post = :type, 
        	id_product   = :produto, 
        	path_post    = :arquivo, 
        	comments     = :comment
        	WHERE id = :id ");
        $post_edit->bindParam(':titulo',  $this->formData['titulo_post']);
        $post_edit->bindParam(':type',    $this->formData['id_type_post']);
        $post_edit->bindParam(':produto', $this->formData['id_produto']);
        $post_edit->bindParam(':arquivo', $this->formData['path_post']);
        $post_edit->bindParam(':comment', $this->formData['comment']);
        $post_edit->bindParam(':id',      $this->formData['id']);
        $post_edit->execute();

	}

    /**
    * save data of form user 
    *
    */
	public function userEdit( array $data ) 
	{
        $conexao = $this->connect();
        $this->formData = $data;
        $usr_edit = $conexao->prepare(" UPDATE tb_users
        	SET login_user   = :login_user, 
	        	name_user    = :name_user, 
	        	id_companies = :empresa, 
	        	id_product   = :produto,
	        	id_level     = :nivel
        	WHERE id = :id_user ");
        $usr_edit->bindParam(':login_user', $this->formData['user_login']);
        $usr_edit->bindParam(':name_user',  $this->formData['name_user']);
        $usr_edit->bindParam(':empresa',    $this->formData['id_companie']);
        $usr_edit->bindParam(':produto',    $this->formData['id_produto']);
        $usr_edit->bindParam(':nivel',      $this->formData['id_level']);
        $usr_edit->bindParam(':id_user',    $this->formData['id']);
        $usr_edit->execute();

    }

    public function delete(int $post_id) 
    {   
        $conexao = $this->connect();
        $post_delete = $conexao->prepare(" DELETE t1.*, t2.*, t3.*  
        FROM tb_posts AS t1 
          LEFT JOIN tb_question AS t2 ON t2.id_post = t1.id 
          LEFT JOIN tb_answers  AS t3 
              ON t3.id_question = t2.id 
          WHERE t1.id = $post_id ");
          $post_delete->execute();	
    }

    public function userDelete(int $user_id ) 
    {
        $conexao = $this->connect();
        $usr_delete = $conexao->prepare(" DELETE FROM tb_users
        WHERE id = $user_id ");
        $usr_delete->execute();	
    }

    /**
    * @param recebe array $_FILES
    * @param vefifica se o arquivo é img/vídeo
    * obtém extensão e move p/ pasta de destino no servidor
    */
    public function __uploadFile(array $file = null) : string
    {

		  if($_FILES) 
		  {
	     	 $this->filename = $_FILES['path_posts'];
			 $pathinfo = pathinfo($_FILES['path_posts']['name']);
			 $extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
			 $this->filename['name'] = time().uniqid(date("ymd")) .'.'.$extension;
           
			 if( IOFile::savePath( $extension ) == 1) 
			 {
                $directory = 'App/static/arquivo/video/';
			    move_uploaded_file($this->filename['tmp_name'], $directory.''.$this->filename['name']);
			 } 
			 else {
			   $this->validateExtensionImage( $extension );
			 }
	      } 
	      else {
		   	 throw new FileNotFoundException("Erro ao incluir o arquivo");
		  } 
		  $name_arquivo = !empty($_FILES['path_posts']['name']) ? $this->filename['name'] : '';
		  return $name_arquivo;

    }

    private function validateExtensionImage($extension) 
    {       $extension = $extension;
    		if ($extension == 'jpg' ) {
			   	$this->directory = 'App/static/arquivo/imagens/jpg/';
			} else if($extension == 'png') {
			   	$this->directory = 'App/static/arquivo/imagens/png/';
			} else if ($extension == 'gif') {
                $this->directory = 'App/static/arquivo/imagens/gif/';
			} else if ($extension == 'bmp') {
			    $this->directory = 'App/static/arquivo/imagens/bmp/';
			}
			else if ($extension == 'jpeg') {
			   $this->directory = 'App/static/arquivo/imagens/jpeg/';
		   }  
		   return  move_uploaded_file($this->filename['tmp_name'], $this->directory.''.$this->filename['name']);
    }

}



