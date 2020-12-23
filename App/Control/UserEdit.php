<?php

use App\Model\Product;
use App\Model\PostList;
use App\Model\PostSave;
use Components\Page\Page;
use App\Model\UserListOfALL;
use Components\Message\Message;
use App\Model\EmployerList;
use Components\Exception\FileNotFoundException;

/**
* @author Valdir Silva
* class p/ editar dados do usuário
*
*/
class UserEdit implements Page {

    public function formEdit() 
    {
      $loader = new Twig\Loader\FilesystemLoader("App/Templates");
      $twig   = new Twig\Environment($loader);
      // $template = $twig->loadTemplate('formeditaruser.html');
      $replaces  = array();

     /**
      * @var $form_edit_user
      * recebe o array do formulário
      * 
      */
      $form_user = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if (isset($form_user['editar_user'])) {
         unset($form_user['editar_user']);
         $form_edit_data[] = $form_user;
      }

      if (isset($form_user['excluir_user'])) {
          unset($form_user['excluir_user']);
          $form_delete_usr = new PostSave;
          $form_delete_usr->userDelete($form_user['id']);
          header("Location: index.php?class=UserAll");
      }

      if (isset($form_user['reset_password'])){
          // var_dump($form_user['user_id']);
          unset($form_user['reset_password']);
          $form_reset_password  = new PostSave;
          $form_reset_password->resetPassword($form_user['user_id']);
          header("Location: index.php?class=UserAll");
      }

      $replaces['usuarios_list'] = isset($form_edit_data) ? $form_edit_data : '';
      /** create instance of obj  product return array data **/
      $listProduct = new Product;
   	  $list_prod   = $listProduct->getProducts();
   	  if ($list_prod) {
   	     /** creates a position in the replace array **/
          $replaces['produtos'] =  $list_prod;
   	  }

   	  /**
   	  * @var create instance of employe return array data 
   	  *
   	  **/

      $company = new EmployerList;
      $list_company = $company->getListCompany();
      if ($list_company) {
          $replaces['empresas'] = $list_company;
      } 

     /**
     * @var create instance of employe return array data 
     *
     */

      $user_level = new UserListOfALL;
      $list_level = $user_level->getAccessListUser();
      if ($list_level) {
          $replaces['nivel_acesso'] = $list_level;
      }

      return  $content = $twig->render('formeditaruser.html', $replaces);
    }

    /**
    * @var receiver data to form
    * @var create instance in to database class model 
    */

    public function save() 
    { 
      $form_save_data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    	if (isset($form_save_data['update_usr'])) {
          unset($form_save_data['update_usr']);
          $data_form_save = new PostSave;
          $data_form_save->userEdit($form_save_data);
          header("Location: index.php?class=UserAll");
    	}
    }

    /**
    * @return render template to view 
    *
    */

    public function show() 
    {
    	print $this->formEdit();
    }
}

