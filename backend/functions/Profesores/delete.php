<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 

    $consulta = "DELETE FROM profesores WHERE ci_profesor=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    Header("location: ../../../frontend/Mostrar_informacion.php");
?>