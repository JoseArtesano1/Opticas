
<?php 

function conectarDB (): mysqli{

   // $db= mysqli_connect('127.0.0.1','root','root','optica');
  // $db= new mysqli('127.0.0.1','root','root','optica');
 $db= new mysqli( $_ENV['DB_HOST'],  $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_DB']);
    $db->set_charset("utf8");

    if(!$db){
        echo "Error no se puede conectar";
        exit;  //para evitar mostrar informacion importante
    }
    return $db;
 }