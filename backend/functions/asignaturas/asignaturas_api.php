<?php

include_once ('../../db/conexion.php');
include_once ('asignaturas_func.php');

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

$con = conectar_a_bd();

if(isset($_POST['registrarAsignatura'])){
    $nombre_asignatura = strip_tags(trim($_POST['nombreAsignatura']));

    $existe = consultar_si_existe_asignatura($con, $nombre_asignatura);

    $insertar_datos = insert_datos_asignatura($con, $existe, $nombre_asignatura);

    echo json_encode($insertar_datos);

}

?>