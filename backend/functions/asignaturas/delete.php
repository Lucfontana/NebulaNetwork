<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
    $consulta = "DELETE FROM asignaturas WHERE id_asignatura='$?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    Header("location: ../../../frontend/asignaturas.php");
?>