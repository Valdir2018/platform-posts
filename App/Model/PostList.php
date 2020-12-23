<?php


declare(strict_types=1);


namespace App\Model;
use Components\Session\Session;

use PDO;
/**
* @return Lista todas as postagens
*
*/

class PostList 
{

   private $result;
   private $conn;

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

   public function viewTitle() 
   {
      $list_post = new \App\Model\Helper\Read();
      $list_post->fullRead("SELECT id, titulo_post  FROM tb_posts ORDER BY id DESC  ");
      $this->result = $list_post->getResult();
      return $this->result;
   }

   public function dataListPost( $id_session ) 
   {
      $list_post = new \App\Model\Helper\Read();
      if (Session::getValue("id_level") == 2) {
          $list_post->fullRead("SELECT  *, DATE_FORMAT(date_post,'%M/%d/%Y') AS f_date,
          TIME_FORMAT(date_post, '%T') AS f_hora FROM tb_posts WHERE p_status = 1  ORDER BY f_date DESC  ");
      } else {
          $list_post->fullRead("SELECT  *, DATE_FORMAT(date_post,'%M/%d/%Y') 
              AS f_date, TIME_FORMAT(date_post, '%T') AS f_hora 
           FROM tb_posts 
              WHERE id_product = $id_session OR id_product = '3' AND p_status = 1
          ORDER BY f_date DESC  ");
      }
      $this->result = $list_post->getResult();
      return $this->result;
   }
   
   
   public function all() 
   {
      $list_all_post = new \App\Model\Helper\Read();
      $list_all_post->fullRead("SELECT *  FROM tb_posts ORDER BY id DESC  ");
      $this->result = $list_all_post->getResult();
      return $this->result;
   }

   public function allUsers() 
   {
      $list_all_usr = new \App\Model\Helper\Read();
      $list_all_usr->fullRead("
      SELECT   usr.id, usr.login_user, usr.name_user, comp.id_companie AS idcomp, comp.name_companie 
          AS empresa, prod.name_product 
          AS produto, prod.id_product, niva.id_level AS idniv, niva.name_level AS nivel 
        FROM tb_users AS usr
            INNER JOIN tb_companies AS comp ON comp.id_companie = usr.id_companies
            INNER JOIN tb_product   AS prod ON prod.id_product  = usr.id_product
            INNER JOIN tb_level     AS niva ON niva.id_level    = usr.id_level");
      $this->result = $list_all_usr->getResult();
      return $this->result;
   }


   /**
   * @return get id questions
   *
   */
   public function dataListGetId() 
   {
      $data_list = new \App\Model\Helper\Read();
      $data_list->fullRead("SELECT id, id_post, description_question FROM tb_question ORDER BY id DESC ");
      $this->result = $data_list->getResult();
      return $this->result;
   }
   public function dataListQuestion(int $post_id) 
   {
      $conexao = $this->connect();
      $stmt    = " 
      SELECT  questao.id, 
              questao.id_post, questao.description_question, questao.text_input
              FROM tb_question  AS questao
          INNER JOIN tb_posts AS post ON post.id = $post_id
              WHERE questao.id_post =  $post_id  
      GROUP BY questao.description_question ASC";
      $retorno = $conexao->prepare($stmt);
      $retorno->execute();
      $resultado = $retorno->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
        
   }

   public function viewData() 
   {
      $conexao = $this->connect();
      $list_view_data = $conexao->prepare("SELECT * FROM tb_questions_recorded");
      $list_view_data->execute();
      $this->result = $list_view_data->fetchAll(PDO::FETCH_ASSOC);
      return $this->result;
    }

   public function countLikes(int $postid) 
   {
      $conexao = $this->connect();
      $count_liked_post = $conexao->prepare('SELECT COUNT(liked) AS liked FROM tb_liked WHERE id_post = :id');
      $count_liked_post->bindParam(':id', $postid);
      $count_liked_post->execute();
      $this->result = $count_liked_post->fetch(PDO::FETCH_ASSOC);
      return $this->result;   
   }

   public function countDisliked(int $postid) 
   {
      $conexao = $this->connect();
      $count_liked_post = $conexao->prepare('SELECT COUNT(desliked) AS desliked FROM tb_liked WHERE id_post = :id');
      $count_liked_post->bindParam(':id', $postid);
      $count_liked_post->execute();
      $this->result = $count_liked_post->fetch(PDO::FETCH_ASSOC);
      return $this->result;   
   }

   public function listAlternativePost($alternative_id) 
   {    
      $conexao = $this->connect();
      $stmt    = "SELECT * FROM tb_answers WHERE id_question = $alternative_id";
      $retorno = $conexao->prepare($stmt);
      $retorno->execute();
      $this->result = $retorno->fetchAll(PDO::FETCH_ASSOC);
      return $this->result;   
   }
   /**
   * @return dataset excel
   */
   public function dataSet() {}
}
