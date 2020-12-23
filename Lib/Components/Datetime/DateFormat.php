<?php

/**
* @author Valdir
* 20/08/2020
*
*/

namespace Components\Datetime;


class DateFormat 
{
  /**
  * @var attr for receive  data format pt-br
  *
  */
  public static $date_publicaction;

  /**
  * @var recebe a data de publicação
  * @return retorna data formatada pt-br
  */

  public static function formatDate($date_public) 
  {   
      $mes = explode("/", $date_public);
      $meses_do_ano = array(
      'January' => 'Janeiro', 'February'  => 'Fevereiro', 'March'   => 'Março',
      'April'   => 'Abril',   'May'       => 'Maio',      'June'    => 'Junho', 'July' => 'Julho', 
      'August'  => 'Agosto',  'September' => 'Setembro',  'October' => 'Outubro',  'November'=> 'Novembro', 'December' => 'Dezembro' );
      foreach ($meses_do_ano as $key => $value) {
         if ($key === $mes[0]) {
             self::$date_publicaction = $value . '/' . $mes[1] . '/' . $mes[2];
         }
      }
      return self::$date_publicaction;
  }

}