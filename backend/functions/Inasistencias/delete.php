<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id_inasistencia = $_GET['id'];
 
    //colsulta que elimina inacistencias
    $consulta = "DELETE FROM inasistencia WHERE id_inasistencia=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id_inasistencia);
    $stmt->execute();
    $stmt->close();

// carga la pantalla con la tabla actualizada
    Header("location: ../../../frontend/Mostrar_informacion.php");
?>