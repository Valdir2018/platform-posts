<?php



use App\Model\PostList;
use Components\Exception\FileNotFoundException;


class DataSetCSV 
{
	private $table;
	private $column;
	private $header;

	public function CsvToDataSet()  
	{  
        if (true) {
           $table = $this->createTable();
           $create_new_dataset = new PostList;
           $dataset = $create_new_dataset->dataSet();
           foreach ( $dataset as $linha ) {
               $this->column .= "<tr>";
               $this->column .= "<td>". $linha[''] ."</td>";
               $this->column .= "</tr>";
           }
           $table .= $this->column;
           $this->forceDownload();
        }
	}

	public function createTable()   
	{ 
        $this->table = "<table border='1'>";
        $this->column = "<tr>";
        foreach($this->header as $key => $value ) {
          $this->column .= "<th>". $value. "</th>";
        }

        $this->column .= "</tr>";
        $this->table  .=  $this->column;
        return   $this->table; 	

	}

	public function forceDownload($filename) 
	{ 
		$table = $filename;
	    $name_file = "dataset.xls"; 
	    header('Content-Type: application/vnd.ms-excel'); # Configurações header para forçar o download  
	    header('Content-Disposition: attachment;filename="'.$name_file.'"');
	    header('Cache-Control: max-age=0');
	    header('Cache-Control: max-age=1');  #  Se for o IE9, isso talvez seja necessário
	    print $table;  
	    exit;


  }
  
  public function show() 
  {
    print "<h3 align='center'>Página exportar</h3>";
  }
}