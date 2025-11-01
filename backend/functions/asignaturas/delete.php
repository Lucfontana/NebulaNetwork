<?php
include_once ('../../db/conexion.php');           

    $connect = conectar_a_bd();

    // Obtiene el parámetro 'id' desde la URL (por método GET).
    // Este valor normalmente viene de un enlace o botón "Eliminar" en otra página.

    //Recibe los parametros mediante metodo post del formulario y los recibe segun el name=""
    $id = $_GET['id'];
 

    //Consulta preparada de eliminar(delete) al cual le cargamos los parametros
    $consulta = "DELETE FROM asignaturas WHERE id_asignatura=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id); // Aca se cargan los parametros, a los cuales les decimos que tipo de datos son y los cargamos mediante variables
    //i es entero, blind_param asigna la variable $id al placeholder ? en la consulta.
    $stmt->execute(); //se ejecuta la consulta, que va a eliminar la asignatura
    $result = $stmt->get_result();
    $stmt->close(); //lo cerramos para que no ocupe espacio, por el limite

    //Redirige a la página que muestra la pagina de asignaturas actualizada
    Header("location: ../../../frontend/Mostrar_informacion.php");
?>