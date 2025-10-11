<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
    $consulta = "DELETE FROM orientacion WHERE id_oriencacion=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    Header("location: ../../../frontend/Orientaciones.php");
?>