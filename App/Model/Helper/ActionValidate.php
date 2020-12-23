<?php


namespace App\Model\Helper;
use PDO;

class ActionValidate 
{
    private $result;
    public function getResult() 
    {
        return $this->result;
    }
    private function connect() 
    {
        $objeto = new \App\Model\Transaction();
        $this->conn = $objeto->getConn();
        return $this->conn;
    }

    public  function isValidate($id_user, $question) 
    {
        $connect = $this->connect(); 
        $validate_action = $connect->prepare("SELECT  count(*) as contagem,  id_users, id_question
            FROM tb_questions_recorded WHERE id_users = $id_user AND id_question = $question ");
        $validate_action->execute();
        $resultado = $validate_action->fetch();
        $count = $resultado['contagem'];
        if ($count !== "0") {
            print "Maior";
            return true;
        } 
    }
}

