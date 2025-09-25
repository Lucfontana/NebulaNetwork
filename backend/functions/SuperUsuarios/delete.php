<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
    $sql = "DELETE FROM superusuario WHERE id_superusuario='$id'";

    $query = mysqli_query($connect, $sql);

    if ($query) {
    Header("location: /frontend/SuperUsuarios.php");
    }
?>