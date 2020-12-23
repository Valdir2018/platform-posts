<?php

use App\Model\Login;
use App\Model\PostList;
use App\Model\PostSave;
use App\Control\Layer\ShowLayer;
use Components\Page\Page;
use Components\File\IOFile;
use Components\Session\Session;
use Components\Datetime\DateFormat;
use Components\Exception\FileNotFoundException;

class ListPost implements Page
{
  
  private $post_id;
  private $content;
  private $template;
  private $session_id;
  private $pws_status; // obtem o status de atualização senha
  private $form = false;
  private $navigation;
  private $menus;

  public $type;

  public function __construct() 
  {
     $this->updatePassword();
     $status_pass = $this->passwordValue(); 
     $this->template = file_get_contents('App/Templates/timeline.html');
     $session =  Session::getValue("id_user");
     $this->session_id = "<p class='username hidden  usr_id' id='userid' style='display:none;'>{$session}</p>";
     $this->pws_status = "<p class='username  hidden usr_id' id='pws' style='display:none;'>{$status_pass}</p>";

  }
  
  public function passwordValue() 
  {  
      $id_usr_session =  Session::getValue("id_user");
      $get_status_update = new Login;
      $data_value = $get_status_update->getValuePassword($id_usr_session);
      return $data_value;
  }
  
  public function renderPostHtml() 
  {   $this->content = '';
      $list_post = new PostList();
      $resultado = $list_post->dataListPost(Session::getValue('produto'));

      foreach( $resultado as $key => $post):
          $this->content .= '<div class="time-label">';
           $this->content .= '<span class="">'. DateFormat::formatDate($post['f_date']) .'</span>';
             $this->content .= '</div>';
            $this->content .= '<div>';
           $this->content .=   '<i class="far fa-star" style="color:white;"></i>';
          $this->content     .= '<div class="timeline-item col-xs-12">';
            $this->content    .= '<span class="time">';
              $this->content .= '<i class="fas fa-clock"></i> '.$post['f_hora'].' </span>';
                $this->content .= '<h3 class="timeline-header">';                                   
                  $this->content .= '<i class="fab fa-slack-hash" style="font-size:35px;color:#005faf"></i>
                    <button class="post-tag">'. $post['titulo_post'].'</button> </h3>';
                  $this->content .= '<div class="timeline-body">';
                $extensao = substr($post['path_post'], -4);
                $path = explode(".", $post['path_post']);
                if (!empty($post['path_post'])):
                     if( IOFile::extension($extensao) == 'video' ):

                        $this->content .= '<video  class="post-media"  poster="App/static/_img/poster-video2.png" width="600" controls controlsList="nodownload">';
                        $this->content .= '<source src="App/static/arquivo/video/'.$post['path_post'].'"  type="video/mp4">';
                        $this->content .= '<source src="App/static/arquivo/video/'.$post['path_post'].'"  type="video/webm">';
                        $this->content .= ' Desculpe; seu navegador não suporta vídeos HTML5 em WebM com VP8 ou MP4 com H.264.';
                        $this->content .= '</video>';

                      else:
                        $this->content .= '<img src="App/static/arquivo/imagens/'.$path[1].'/'.$post['path_post'].'"  class="img-rounded" > ';
                      endif;
                  endif;
              
                  $this->content .= '</div>';
                  $this->content .= '<div class="timeline-footer">';

                  $query_list_questoes = $list_post->dataListQuestion($post['id']);
                  $this->content .= '<div class="panel">';
                  if (isset($post['comments'])) {
                      $this->content .= '<div class="p-title">'. $post['comments']. '</div>';

                  }
                    // crio um array p/ poder acessar no layer atraves da chave e valor
                    $data_post = array(
                        'postid' => $post['id'], 
                        'userid' => Session::getValue("id_user") 
                    );

                    $data_layer_render = new ShowLayer; 
                    $this->content .= $data_layer_render->showDataLayer( $query_list_questoes, $list_post, $data_post );
                                        
           
                  $this->content .= '</div>';
                 $this->content .= '<p id="message_sucess" style="display:none;">Resposta Salva com sucesso ! 
                 <span class="material-icons">done_all</span></p>';
               $this->content .= '</div>';
               $this->content .= '<input type="hidden" name="id_question"  data-js="valor_id" class="valor_id"
                           value="'.$post['id'].'">';
              $data_count_liked = $list_post->countLikes($post['id']);
              $data_count_desliked = $list_post->countDisliked($post['id']);
                             
            $this->content .= '<div class="favorito">';
              foreach ($data_count_liked as $likes  ):
                 $this->content .= '<p id="icon-curtir" data-touserid="'.$post['id'].'">
                      <small  id="icon-cont-curt_'.$post['id'].'" class="icon-cont-curt" data-touserid="'.$post['id'].'" >'.$likes.'</small>';
              endforeach; 
                 $this->content .= '<span class="liked material-icons" id="favorite-icon-ok" data-touserid="'.$post['id'].'" value="'.$post['id'].'" >favorite</span>';
                  $this->content .= '<small id="favorite-icon-title">curtir</small>';  

             $this->content .= '</p>';
             foreach ($data_count_desliked as $deslikes  ):
                  $this->content .= '<p id="icon-descurtir" data-touserid="'.$post['id'].'" > 
                  <small id="icon-cont-descurt_'.$post['id'].'" class="icon-cont-descurt" >'.$deslikes.'</small> ';
              endforeach; 
                $this->content .= '<span class="material-icons" id="favorite-icon-not" data-touserid="'.$post['id'].'" value="'.$post['id'].'">thumb_down</span>';  
                   $this->content .= '<small id="favorite-icon-title-descutir">descurtir</small>';  
              $this->content .= '<form class="form-inline" id="form-comments"><div class="form-group">';

                  $this->content .= '<i class="far fa-comments fa-2x"></i>';
                  $this->content .= '<input type="hidden" id="postID" value="'.$post['id'].'"/>';
                   $this->content .= '<input type="text" class="form-control" id="comments" placeholder="comentar" name="comment">
                   <span class="material-icons button-comment-send" onclick="saveComment()">send</span>';
              $this->content .= '</div> </form>';
            $this->content .= '</p>';
           $this->content .= '</div>';
            $this->content .= $data_layer_render->showIconStar();

           $this->content .= '</div>';
          $this->content .= ' </div>';            
        endforeach;

        
      return $this->content; 

  }

  public function getJSON() 
  {
     $data_id = filter_input_array(INPUT_GET, FILTER_DEFAULT);
     if ( !empty($data_id) ) {
           $data_update_liked = new PostList;
           $count = $data_update_liked->count($data_id['post_id']);
           echo json_encode($count); 
     }
  }

  public function updatePassword() 
  {
      $data_form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      $save_update_form = new PostSave();
      if ( isset($data_form["Atualizar"]) ) {
           unset($data_form["Atualizar"]);
           $save_update_form->updatePassword( $data_form );
      }
  }

  // Save liked post
  public function saveLiked() 
  {
      $data_form_liked = filter_input_array(INPUT_POST,  FILTER_DEFAULT);
      $data_save_liked = new PostSave;
      if ( !empty($data_form_liked) ) {
           $data_save_liked->saveLiked($data_form_liked);   
      }
  }

  public function saveDesliked() 
  {
      $data_form_desliked = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      $data_save_desliked = new PostSave;
      if ( !empty($data_form_desliked)) {
         $data_save_desliked->saveDesliked($data_form_desliked);
      }

  }

	public function show() 
  {  
     try {
        print  $session_id = isset($this->session_id) ? $this->session_id : '';
        print  $resultado  = isset($this->pws_status) ? $this->pws_status : '';
        $conteudo = $this->renderPostHtml();
        $this->content = str_replace('{conteudo}', $conteudo, $this->template);
        print $this->content;
     } catch(FileNotFoundException $err) {
        throw new FileNotFoundException("Houve um erro interno ao carregar essa página!");
     }
  }
}




