<?php


namespace Components\Message;



class Message 
{ 

    public static function redirect($time, $page, $type, $message) 
    {
        $url = '<meta http-equiv="refresh" content="'.$time.'; url='.$page .'">';
        print "<div class='alert alert-{$type}' style='width:97%;margin:auto 3%;'>".$message."</div><br/>"; 
        print $url;

    }

}