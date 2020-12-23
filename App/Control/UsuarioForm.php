<?php


use Components\Page\Page;
use App\Model\Product;
use App\Model\CreateNewUser;
use App\Model\UserListOfALL;
use App\Model\EmployerList;
use Components\Message\Message;
use Components\Exception\FileNotFoundException;

class UsuarioForm implements Page
{
    private $data_form;
    private $message_sucess;

	public function get() 
	{  
        if (isset($_REQUEST['method']) == 'reset') {
            $this->reset();
        }

		$loader = new Twig\Loader\FilesystemLoader('App/Templates');
		$twig   = new Twig\Environment($loader);
		// $template = $twig->loadTemplate('form-cadastrar.html');

		// array for replaces 
        $replaces = array();

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
       
        if (isset($_POST['cadastrar'])) {
            if( $this->save() ) {
                Message::redirect(2, 'index.php?class=UsuarioForm', 'success', 'Usuário(s) cadastrado com sucesso !');
            }
            else {
                Message::redirect(2, 'index.php?class=UsuarioForm', 'danger', "Houve um erro ao cadastrar {$this->data_form['mat_user']} esse usuário !");
            } 
        } 

		return $twig->render('form-cadastrar.html', $replaces);
	}

    public function save() 
    {  
        $this->data_form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($this->data_form['cadastrar']))  {
             unset($this->data_form['cadastrar']);
             $save_user   = new CreateNewUser();
             $save_user->saveUsers($this->data_form);
             if ( $save_user->getUser() ) {
                  $this->message_sucess = true;     
             } 

        }
        return $this->message_sucess;
    }


    // página de reset de senha
    public function reset() 
    {
        $data_form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if ( isset( $data_form['resetar']) ) {
             unset($data_form['resetar']);
             $form_user = new CreateNewUser;
             $form_user->resetPasswordUser( );
        }
    }

	public function show() 
	{
		try {
			print $this->get();
		} catch(FileNotFoundException $error) {
            throw new FileNotFoundException("Houve um erro interno ao carregar essa página!");
		}
	}
}