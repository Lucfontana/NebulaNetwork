<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
    $consulta = "DELETE FROM recursos WHERE id_recurso=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    Header("location: /frontend/Recursos.php");
?>