<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id_reserva = $_GET['id'];
 
    $consulta = "DELETE FROM reservas_espacios WHERE id_reserva=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id_reserva);
    $stmt->execute();
    $stmt->close();


    Header("location: ../../../frontend/Mostrar_informacion.php");
?>