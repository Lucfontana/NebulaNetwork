<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
    $sql = "DELETE FROM cursos WHERE id_curso='$id'";

    $query = mysqli_query($connect, $sql);

    if ($query) {
    Header("location: /frontend/Cursos.php");
    }
?>