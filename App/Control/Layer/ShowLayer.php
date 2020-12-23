<?php


namespace App\Control\Layer;
use App\Model\PostList;




class ShowLayer {

	public $content;
    
    /**
    * showDataLayer = deve receber uma instância do objeto PostList,e um array de dados
    *
    */
	public function showDataLayer(array $postList, PostList $object, $post) {
        $data_layer_list = $postList;
        $data_list_alternative = $object;
        foreach($data_layer_list as $pergunta):
           $this->content .= '<p class="p-title">'.$pergunta['description_question'].'</p>';
           $data_result =  $data_list_alternative->listAlternativePost($pergunta['id']);  
 
	        foreach( $data_result as $resposta):
	            if(isset($resposta['id_question'])):
	              $this->content .= '<div class="radio">';
	               $this->content .= '<label class="checkcontainer">';
	                 $this->content .= '<input type="radio" class="teste"  id="get" name="'.$pergunta['id'].'" value="'.$resposta['id_answers'].'" data-js="'.$pergunta['id_post'].'"> ';
	                  $this->content .= '<span class="radiobtn"></span>';
	               $this->content .= $resposta['description_answers'].'</label>';
	             $this->content .= '</div>';
	         endif;
	       endforeach;

	        if ($pergunta['text_input'] == '1' ):
                $this->content .= '<form method="POST" action="index.php?class=PostForm&method=addFieldComment">';
                $this->content .= '<div class="flex-coment-justify" id="">';
                    $this->content .= '<i class="item fas fa-plus"  name="'. $pergunta['id'] . ' " value="'. $pergunta['id'] . ' "   id="show" onclick="handleClickOpenField(this)" > Justificar</i>';
                    
                    $this->content .= '<input type="text" class="form-control pergunta" data-id="perguntaid"  id="pergunta_'.$pergunta['id']. '"  name="commet_justify"  style="display:none"> ';

                    $this->content .= '<input type="hidden"  class="form-control " name="questaoId"   value="'.$pergunta['id'] .'" > ';
                    $this->content .= '<input type="hidden"  class="form-control " name="postagemId"  value="'.$post['postid'] .'" > ';
                    $this->content .= '<input type="hidden"  class="form-control"  name="usuario"     value="'.$post['userid'] .'" > ';

                    $this->content .= '<button type="submit"   class="btn btn-xs btn-success save" id="button_'.$pergunta['id'] . '" style="display:none" onclick="handleClickButton(this),saveField(this)" >Salvar</button>';

                $this->content .= '</div>';  
                $this->content .= '</form>';    
            endif;                  
           
	     endforeach;   
     return $this->content;
	}


	public function showIconStar() {
		   $this->content = '<div class="col-md-4 flex-avaliacao">';
              $this->content .= '<i class="far fa-star fa-2x" name="star_1" onmouseover="handleStarPost(this)" ></i>';
              $this->content .= '<i class="far fa-star fa-2x" name="star_2" onmouseover="handleStarPost(this)" ></i>';
              $this->content .= '<i class="far fa-star fa-2x" name="star_3" onmouseover="handleStarPost(this)" ></i>';
              $this->content .= '<i class="far fa-star fa-2x" name="star_4" onmouseover="handleStarPost(this)" ></i>';
              $this->content .= '<i class="far fa-star fa-2x" name="star_5" onmouseover="handleStarPost(this)" ></i>';
           $this->content .= '</div>';
        return $this->content;
	}



}

