<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    $id = $_GET['id'];

    $consulta = "DELETE FROM superusuario WHERE id_superusuario=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close(); 
    Header("location: ../../../frontend/Mostrar_informacion.php");
?>