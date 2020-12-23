<?php


use App\Model\Product;
use App\Model\PostSave;
use App\Model\PostList;
use Components\Page\Page;
use Components\Message\Message;
use Components\Session\Session;
use Components\Exception\FileNotFoundException;

class PostForm implements Page 
{
	 private $result;
   private $data_form;

   public function get() 
   {
      $loader = new Twig\Loader\Filesystemloader("App/Templates");
      $twig   = new Twig\Environment($loader);
      // Vetor de param p/ o template
      $replaces = array();

      $list_product = new Product;
      $resultado   =  $list_product->getProducts();
      $replaces['produtos'] =  $resultado;

      // Lista os posts e gera um novo array
      $list_posts = new PostList;
      $data_list  = $list_posts->viewTitle();
      foreach ($data_list as $key => $value ) {
          $data_value[] = $value;
      } 

      $replaces['posts'] = isset($data_value) ? $data_value : '';
      $list_all = $list_posts->all();
      foreach($list_all as $key => $value) {
          $data_posts_all[] = $value;
      }

      $replaces['post_all'] = isset($data_posts_all) ? $data_posts_all : '';

      /**
      *
      * @var replaces contém os valores a ser renderizados na view
      *
      */

      return $content = $twig->render('postagem.html', $replaces);
   }

    /**
    *
    * @var cria uma postagem
    *
    */

    public function savePost() 
    {
      $form_data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if (!empty($form_data) ) {
          unset($form_data['cadastrar']);
          $save_data = new PostSave;
          $save_data->savePostForm($form_data);
          header("Location: index.php?class=PostForm");
      }
    }

    /**
    * Recebe os dados da requisição http ajax [ JSON ]
    * Envia os dados p/ a classe responsável pela persistência dos dados
    *
    */    
    public function saveForm() 
    {
       $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       if (!empty($dados)) {
           $save_data_form = new PostSave;
           $insert_success  = $save_data_form->saveAnwers($dados);
           if(  $insert_success ) {
                return true;
           }
       }
    }

    /**
    *
    *@var add questões a uma pergunta
    */

    public function addFormPost() 
    { 
       $data_form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       $object = json_decode(json_encode($data_form),true);
        foreach ( $object as $key => $value){
           $valor = (array) json_decode($key);
           foreach($valor as $question) {
              $array_elements[] = $question;
           } 
        }
        $add_form = new PostSave;
        $add_form->addFormQuestion($array_elements[0], $array_elements[1]);
    }

    /**
    *
    * @var add comment post
    */

    public function addComent() 
    {
       $data_form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       $json_decode = json_decode(json_encode($data_form), true);
       foreach($json_decode as $key => $value ) {
           $valor = (array) json_decode($key);
           foreach($valor as $comment) {
               $array_elements[] = $comment;
           }
       }

       $add_form_comment = new PostSave;
       $add_form_comment->saveAddComent($array_elements[0], $array_elements[1], $array_elements[2]);  
    }
    
    // Recebe os dados da requisição
    public function addFieldComment() 
    {
       $data_form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       if ( !empty( $data_form )) {
            $add_field_comment = new PostSave;
            $add_field_comment->addFieldComment(
            $data_form['usuario'],$data_form['postagemId'], $data_form['questaoId'], $data_form['commet_justify']);
            header("Location: index.php?class=ListPost");
       }
      
    }

    public function addAlternative() 
    {
       $data_form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       $json_decode = json_decode(json_encode($data_form), true);
       foreach($json_decode as $key => $value ) {
           $valor = (array) json_decode($key);
           foreach($valor as $alternative) {
               $array_elements[] = $alternative;
           }
       }  
       $add_form_data = new PostSave;
       $add_form_data->addFormAlternative($array_elements[0], $array_elements[1], $array_elements[2] );     
    }

    private function paramExists($param) 
    {
       return isset($param) ? $param : '';
    }

    public function show() 
    {  
      try {
         print $this->get();
       } catch(FileNotFoundException $err) {
          throw new FileNotFoundException("Houve um erro interno ao carregar essa página!");
       }
    }
}