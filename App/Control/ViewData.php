<?php


use App\Model\PostList;
use App\Components\Page\Page;


class ViewData 
{
	private $data;
    private $template;


	public function render() 
	{
        $loader = new Twig\Loader\FilesystemLoader('App/Templates');
        $twig   = new Twig\Environment($loader);
        // $template = $twig->loadTemplate("data-result.html"); 
        $replaces = array();
        $view_data = new PostList();
        $dataset = $view_data->viewData();
        return $content = $twig->render('data-result.html', $replaces);

    }
    
	public function show() 
	{   
	    print $this->render();
	} 
}


