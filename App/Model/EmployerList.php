<?php




namespace App\Model;
use Helper;
use Components\Exception\FileNotFoundException;

/**
*
* @return 
* *
* class responsável por listar as empresas 
* persistidos no banco de dados, devolve um array como retorno
* 
*/


class EmployerList  
{
	private $result;

	/**
	* Getters - Obtém o resultado
	*
	*/
	public function getResult() 
	{
		return $this->result;
	}
	public function getListCompany() 
	{
		$company_list = new \App\Model\Helper\Read();
		$company_list->fullRead("SELECT * FROM tb_companies");
		$this->result = $company_list->getResult();
		return $this->result;
	}
}