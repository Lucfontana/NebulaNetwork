<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();
    
    //Obtiene el parámetro id que viene por URL (método GET).
    $id = $_GET['id'];
 
    //Define una consulta SQL que eliminará un registro de la tabla recursos cuyo campo id_recurso 
    // coincida con el valor recibido.
    $consulta = "DELETE FROM recursos WHERE id_recurso=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    Header("location: ../../../frontend/Mostrar_informacion.php");
    //Redirige al usuario automáticamente hacia la página que muestra la lista actualizada de recursos
?>