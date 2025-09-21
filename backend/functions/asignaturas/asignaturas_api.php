<?php

include_once ('../../db/conexion.php');
include_once ('asignaturas_func.php');

$con = conectar_a_bd();

if(isset($_POST['registrarAsignatura'])){
    $nombre_asignatura = strip_tags(trim($_POST['nombreAsignatura']));

    $existe = consultar_si_existe_asignatura($con, $nombre_asignatura);

    insert_datos_asignatura($con, $existe, $nombre_asignatura);

}

?>