<?php



namespace App\Model\Helper;



class FormValidate 
{
	private $data_form;
	private $result;


	public function getResult() 
	{
         return $this->result;
	}
	public function validateFormData($data) 
	{   $this->data_form = $data;
		$this->data_form = array_map('strip_tags', $this->data_form); 
        $this->data_form = array_map('trim', $this->data_form);       
        if(in_array('', $this->data_form)){
           $this->result = false;
        } 
        else {
           $this->result = true;	
        }
        return $this->result;
	}
	public function stripTags($data) 
	{
		$this->data_form = $data;
		$this->data_form = array_map('strip_tags', $this->data_form); 
        if(empty($this->data_form)){
           $this->result = false;
        } 
        else {
           $this->result = true;	
        }
        return $this->result;
	}
}