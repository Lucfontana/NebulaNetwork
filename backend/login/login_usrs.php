<?php

include_once('../db/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['loginUsuario'])){

        $ci_usuario = (int)$_POST["CI"];
        $password = $_POST['contrasena'];
        
    }

}
?>