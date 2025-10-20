<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id_inasistencia = $_GET['id'];
 
    $consulta = "DELETE FROM inasistencia WHERE id_inasistencia=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id_inasistencia);
    $stmt->execute();
    $stmt->close();


    Header("location: ../../../frontend/Mostrar_informacion.php");
?>