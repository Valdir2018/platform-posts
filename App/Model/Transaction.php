<?php

namespace App\Model;
use App\Model;

/**
 * 
 * Sub Classe responsável por obter a conexão
 *  
 */
class Transaction extends Connection
{

    public $conexao;
    public function getConn() {
        // acessa a superclasse
        $this->conexao = parent::getConnect();
        return $this->conexao;
    }
}