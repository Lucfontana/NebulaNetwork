<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();

    //Recibe los parametros mediante metodo post del formulario y los recibe segun el name=""
    $id = $_GET['id'];
 

    //Consulta preparada de eliminar(delete) al cual le cargamos los parametros
    $consulta = "DELETE FROM asignaturas WHERE id_asignatura=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id); // Aca se cargan los parametros, a los cuales les decimos que tipo de datos son y los cargamos mediante variables
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close(); //lo cerramos para que no ocupe espacio, por el limite

    Header("location: ../../../frontend/Mostrar_informacion.php");
?>