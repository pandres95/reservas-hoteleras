<?php

require_once('db_connect.php');

// Crear cliente
function create_user($u, $docid, $f_nacimiento, $p_nombre, $s_nombre, $apellidos) {
    
    $response = null;
    
    try{
        
        $conn = database_connect();
        
        /* Nombre de usuario */
        $u ="'" . $conn->real_escape_string($u) . "'";
        /* Documento de Identidad */
        $docid = "'" . $conn->real_escape_string($docid) . "'";
        /* Fecha de Nacimiento */
        $f_nacimiento = $conn->real_escape_string($f_nacimiento);
        $datetime = new DateTime($f_nacimiento);
        $f_nacimiento = "'" . $datetime->format('Y-m-d H:i:s') . "'";
        /* Primer nombre */
        $p_nombre = "'" . $conn->real_escape_string($p_nombre) . "'";
        /* Segundo nombre */
        $s_nombre = "'" . $conn->real_escape_string($s_nombre) . "'";
        /* Apellidos */
        $apellidos = "'" . $conn->real_escape_string($apellidos) . "'";
        
        $sql = "INSERT INTO cliente (pk_codigo_cliente, i_usuario, v_documento_identidad, f_fecha_nacimiento, n_primer_nombre, n_segundo_nombre, n_apellidos)" .
            "VALUES (NULL, " .
            "(SELECT pk_id_usuario FROM usuario WHERE i_username = $u LIMIT 1), " .
            "$docid, " .
            "$f_nacimiento, " .
            "$p_nombre, ".
            "$s_nombre, ".
            "apellidos)";
        
        if($query = $conn->query($sql) === false) {
            $response = json_encode(array("response"=>false));
        } else {
            $response = json_encode(array("response"=>true));
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        $response = json_enconde(array("response"=>false,"error"=> $e->getMessage()));
        $conn->close();
    }
    
    return $response;
    
}


if(isset($_GET['callback'])){
    
    /* JSONP GET Request */    
    $username = $_GET['username'];
    $docid = $_GET['doc_identidad'];
    $p_nombre = $_GET['primer_nombre'];
    $s_nombre = isset($_GET['segundo_nombre'])? $_GET['segundo_nombre'] : "NULL";
    
    echo $_GET['callback'] . "(" . create_user($username, $docid) . ")";
    
    /* JSON POST Request */
} else {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $hashed = isset($_POST['hashed'])? $_POST['hashed'] : false;
    
    echo create_user($username, $email, $pwd, $hashed);
    
}

?>