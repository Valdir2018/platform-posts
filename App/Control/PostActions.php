<?php

use App\Model\Product;
use App\Model\PostList;
use App\Model\PostSave;
use Components\Page\Page;
use Components\File\IOFile;
use App\Model\UserListOfALL;
use App\Model\EmployerList;
use Components\Exception\FileNotFoundException;


class PostActions 
{
   private $template;
   public function render() 
   {
      $loader = new Twig\Loader\Filesystemloader("App/Templates");
      $twig   = new Twig\Environment($loader);
     
      $replaces = array();  // Vetor de param p/ o template
      // Recebe os dados do form da view p/ editar 
      $data_view = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(isset($data_view['postagem_editar'])) {
         unset($data_view['postagem_editar']);
         $this->template = 'editar-postagem.html';
         $form_edit_data[] = $data_view;
      }

      if(isset($data_view['add_pergunta'])) {
         $data_form_id_post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
         $data_form_post[]  = $data_form_id_post;
         $this->template = 'add-form-question.html';

         $list_perguntas = new PostList;
         $data_list_questions = $list_perguntas->dataListQuestion($data_form_id_post['id']);
         foreach($data_list_questions as $key => $value ) {
              $data_questions[] = $value;
         }
         $replaces['post_questions'] = isset($data_questions) ? $data_questions : '';
      }

      $replaces['editar_postagem'] =  isset($form_edit_data) ? $form_edit_data : '';
      $replaces['perguntas']       =  isset($data_form_post) ? $data_form_post : '';

       /** create instance of obj  product return array data **/
      $listProduct = new Product();
   	  $list_prod   = $listProduct->getProducts();
   	  if ($list_prod) {
   	   /** creates a position in the replace array **/
          $replaces['produtos'] =  $list_prod;
   	  }

      /** create instance of employe return array data **/
      $company = new EmployerList();
      $list_company = $company->getListCompany();
      if ($list_company) {
          $replaces['empresas'] = $list_company;
      } 

      /** create instance of employe return array data **/
      $user_level = new UserListOfALL();
      $list_level = $user_level->getAccessListUser();
      if ($list_level) {
          $replaces['nivel_acesso'] = $list_level;
      }

      return $twig->render($this->template, $replaces);
   } 

   public function action() 
   {

   	  $form_data_post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
   	  if (isset($form_data_post['post_edit'])) {
          unset($form_data_post['post_edit'] );
          $post_edit = new PostSave;
          $post_edit->postEdit($form_data_post);
          header("Location: index.php?class=PostForm");
      }
      if (isset($form_data_post['postagem_excluir'])) {
         unset( $form_data_post['postagem_excluir'] );
         $path = explode(".", $form_data_post['path_post']);
         if ($path[1] == 'mp4') {
             $dir = 'App/static/arquivo/video/'.$form_data_post['path_post'];
         } else {
             $dir = 'App/static/arquivo/'.$path[1].'/'.$form_data_post['path_post'];
         }
         unlink($dir);
         $post_delete = new PostSave;
         $post_delete->delete($form_data_post['id']);
         header("Location: index.php?class=PostForm");
      }
   }

   public  function show() 
   {
      print $this->render();
   }
}