<?php


namespace Components\File;

class IOFile {
   public static $type;
   /**
   *@var tipo de arquivo
   *
   */

   public static function extension($extension) 
   { 
       $video_ext = array('.mp4' => 0, '.avi' => 1,'.mov' => 2, '.wmv' => 3, '.mkv' => 4,'.flv' => 5,'.3gp' => 6, '.ogg' => 7, '.m4v' => 8, 'webm' => 9);
       if (  array_key_exists($extension, $video_ext) ) {
             self::$type = "video";
       }
       else {
           self::$type = "imagem";
       }
       return self::$type; 
   }

   public static function savePath($file) 
   {
        $video_ext = array('mp4' => 0, 'avi' => 1,'mov' => 2, 'wmv' => 3, 'mkv' => 4, 'flv' => 5, '3gp' => 6,'ogg' => 7, '.m4v' => 8, 'webm' => 9);
        $extensao  = array_key_exists($file, $video_ext);
        if ( $extensao ) {
             return true;
        } 
        else {
            return false;
        }
   }

   public static function backPage(){
        print '<script> function goBack() { window.history.back(); } </script>';
        print '<button onclick="goBack()" style="padding:12px;background:orange;color:#fff;border:0;border-radius:20px;width:200px;cursor: pointer;">'. '<i class="glyphicon glyphicon-circle-arrow-left" ></i> Clique aqui para voltar </button> ';
     }
}




