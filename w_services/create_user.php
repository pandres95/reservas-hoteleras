<?php

require_once('db_connect.php');

// Crear usuario
function create_user($u, $e, $p, $hashed) {
    
    $response = null;
    
    try{
        $conn = database_connect();
        
        $u ="'" . $conn->real_escape_string($u) . "'";
        $e ="'" . $conn->real_escape_string($e) . "'";
        $p = $hashed ? "'" . $conn->real_escape_string($p) . "'": "SHA1('" . $conn->real_escape_string($p) . "')";
        
        $sql = "INSERT INTO usuario (pk_id_usuario, fk_tipo_usuario, i_username, u_email, v_password) VALUES (NULL , 'Usuario', $u, $e, UNHEX($p))";
        
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

/* JSONP GET Request */
if(isset($_GET['callback'])){
    
    $username = $_GET['username'];
    $email = $_GET['email'];
    $pwd = $_GET['password'];
    $hashed = $_GET['hashed'];
    
    echo $_GET['callback'] . "(" . create_user($username, $email, $pwd, $hashed) . ")";
    
    /* JSON POST Request */
} else {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $hashed = isset($_POST['hashed'])? $_POST['hashed'] : false;
    
    echo create_user($username, $email, $pwd, $hashed);
    
}

?>