<?php

require '../Model/Connection.php';



function datasetJSON($dados) {
   
    if (!empty($_POST['user_id'])) {
        $data = array();
        $pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=db_voz_operador" , 'root', '');
        
        $sql = $pdo->prepare(" SELECT  COUNT(t4.id_post) AS total, t1.titulo_post 
              AS titulo,  t2.description_question,  t4.id_answers 
            FROM tb_posts AS t1
            INNER JOIN tb_question 
                AS t2 ON t2.id_post = t1.id
            LEFT JOIN tb_questions_recorded 
                AS t4 ON t4.id_question = t2.id AND t4.id_answers
            WHERE t4.id_post = t1.id GROUP BY t4.id_post ");
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);       
        echo json_encode($data);
    }
}

datasetJSON($_POST);
