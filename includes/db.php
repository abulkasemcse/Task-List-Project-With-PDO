<?php 

$dsn="mysql:host=localhost;dbname=project-two";
try{
    $pdo=new PDO($dsn,'root','');
}
catch
    (PDOException $e){
        echo $e->getMessage();
    }


?>