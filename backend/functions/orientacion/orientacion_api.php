<?php

include_once ('../../db/conexion.php');
include_once ('orientacion_func.php');

// Indicar que la respuesta de este PHP es un JSON
// header('Content-Type: application/json');

$con = conectar_a_bd();

if(isset($_POST['registrarOrientacion'])){
    $nombre_asignatura = strip_tags(trim($_POST['nombreOrientacion']));

    $existe = consultar_si_existe_orientacion($con, $nombre_asignatura);

    $insertar_orientacion = insert_datos_orientacion($con, $existe, $nombre_asignatura);

    // echo json_encode($insertar_orientacion);

}

?>