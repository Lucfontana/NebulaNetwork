<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
    $sql = "DELETE FROM recursos WHERE id_recurso='$id'";

    $query = mysqli_query($connect, $sql);

    if ($query) {
    Header("location: /frontend/Recursos.php");
    }
?>