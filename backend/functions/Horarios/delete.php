<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];

 //consulta para eliminar el registro de la tabla
    $sql = "DELETE FROM asignaturas WHERE id_asignatura='$id'";

    $query = mysqli_query($connect, $sql); // ejecuta la consulta en la bd

    if ($query) { //Si la eliminación se realizó correctamente ($query == true), redirige al usuario a la página /frontend/asignaturas.php.
    Header("location: /frontend/asignaturas.php");
    }
?>