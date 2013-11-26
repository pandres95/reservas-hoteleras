<?php

define ("SERVER", "localhost");
define ("USER", "root");
define ("PWD", "");
define ("DB", "reservas_hoteleras");

function database_connect(){

    $connect = new mysqli(SERVER, USER, PWD, DB);

    if($connect->connect_error){
        throw new Exception ('Falló la conexión a la base de datos: '  . $conn->connect_error);
    }

    return $connect;

}

?>