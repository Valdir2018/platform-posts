<?php



use App\Model\PostList;

class DataSet  {

    private static $data;
    private $grafico;

    private $template;



    public  function returnDataJson() 
    {
      	$this->template = file_get_contents('App/Templates/data-result.html');
        $view_data = new PostList();
        $dataset = $view_data->viewData();
        return json_encode($dataset);   
    }


    public function show()
    {
    	// $conteudo = $this->grafico();
    	// $content = str_replace('{teste}', $conteudo, $this->template);
        // print $content;
    }

}


$object = new DataSet();
print $object->returnDataJson();