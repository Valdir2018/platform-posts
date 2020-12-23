<?php


namespace App\Model\Helper;
use App\Model;
use Exception;
use PDO;

class Read  {
    private $conn;
    private $query;
    private $values;
    private $resultado;
    private $result;


    private function connect() {
        $objeto = new \App\Model\Transaction();
        $this->conn = $objeto->getConn();
        return $this->conn;
    }

    public function getResult() 
    {
        return $this->resultado;
    }

   
    public function runSQL($Table, $Terms = null, $ParseString = null) 
    {
        if (!empty($ParseString)) {
            parse_str($ParseString, $this->values);
        }
        $this->query = "SELECT * FROM {$Table} {$Terms}";
        $this->executeSQL();
    }
  
    public function fullRead($query, $ParseString = null) 
    {
        $this->query = (string) $query;
        if(!empty($ParseString)){
            parse_str($ParseString, $this->values);
        }
        $this->executeSQL();
    }
    
    private function executeSQL() {
        $this->getConexao();
        try {
            $this->getInstrucao();
            $this->result->execute();
            $this->resultado = $this->result->fetchAll();
        } catch (Exception $ex) {
            $this->resultado = null;
        }
    }

    private function getConexao() 
    {
        $conexao = $this->connect(); 
        $this->result = $conexao->prepare($this->query);
        $this->result->setFetchMode(PDO::FETCH_ASSOC);
    }

    private function getInstrucao() 
    {
        if ($this->values) {
            foreach ($this->values as $link => $valor) {
                if ($link == 'limit' || $link == 'offset') {
                    $valor = (int) $valor; 
                } 
                $this->result->bindValue(":{$link}", $valor, (is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            }
        }
    }
}