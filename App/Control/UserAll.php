<?php





use App\Model\PostList;
use Components\Page\Page;

/**
* Lista todos os usuários
* created: 19/08/20
*/

class UserAll 
{  
   /**
   *
   * show view
   */
   public function renderTemplate() 
   {
      
      $loader = new Twig\Loader\FilesystemLoader("App/Templates");
      $twig   = new Twig\Environment($loader);
      // $template = $twig->loadTemplate('usuarios.html');
      // Vetor de param p/ o template
      $replaces = array();
      $list_all_usr = new PostList;
      $all_users    = $list_all_usr->allUsers();

      foreach($all_users as $key => $value ) {
          $users_list[] = $value; 
      }

      $replaces['usuarios'] = isset( $users_list ) ?  $users_list : '';
      return $twig->render('usuarios.html', $replaces);
   } 

   public function show() 
   {
   	 print $this->renderTemplate();
   }
}