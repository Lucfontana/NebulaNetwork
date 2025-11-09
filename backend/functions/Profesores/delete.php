<?php

//Este código se encarga de eliminar un profesor de la base de datos

include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];
 
//consulta SQL que elimina el registro de la tabla profesores
    $consulta = "DELETE FROM profesores WHERE ci_profesor=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    Header("location: ../../../frontend/Mostrar_informacion.php");
?>