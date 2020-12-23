<?php

namespace App\Model;
/**
* @return Reponsável por manipular os produtos no banco de dados
*
*
*/


class Product 
{
    private $result;
    /**
    * Getters
    */
    public function getResult()  
    { 
       return $this->result;
    }

    public function getProducts() 
    { 
        $object = new \App\Model\Helper\Read();
        $object->fullRead("SELECT * FROM tb_product ");
        $this->result = $object->getResult();
        return $this->result;
    }
}
