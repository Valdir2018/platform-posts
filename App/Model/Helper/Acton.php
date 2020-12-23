<?php


namespace App\Model\Helper;

class ActionValidate 
{
	private $result;
    public function getValue() 
    {
        return $this->result;
    }
    private function connect() 
    {
        $objeto = new \App\Model\Transaction();
        $this->conn = $objeto->getConn();
        return $this->conn;
    }

	public  function isValidate($id_action) 
	{
        $connect = $this->connect(); 
        $validate_action = $connect->prepare("SELECT  id_question FROM tb_questions_recorded WHERE id_question = $id_action");
        $this->result = $validate_action->getValue();
        return $this->result;
	}
}