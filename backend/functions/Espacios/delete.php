<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
    $sql = "DELETE FROM espacios_fisicos WHERE id_espacio='$id'";

    $query = mysqli_query($connect, $sql);

    if ($query) {
    Header("location: /frontend/Espacios.php");
    }
?>