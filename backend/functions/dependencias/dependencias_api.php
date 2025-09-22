<?php

include_once ('../../db/conexion.php');
include_once ('dependencias_func.php');

if(isset($_POST['registrarDependencia'])){
    // profesor_asignado asignatura_dada hora_inicio hora_final salon_ocupado, curso_dictado
    $profesor_asignado = $_POST['profesor_asignado'];
    $asignatura_dada = $_POST['asignatura_dada'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_final = $_POST['hora_final'];
    $salon_ocupado = $_POST['salon_ocupado'];
    $curso_dictado = $_POST['curso_dictado'];

    $id_dicta = seleccionar_id_dicta($con, $asignatura_dada, $profesor_asignado);

    insert_dicta_ocupa_espacio($con, $id_dicta, $salon_ocupado);
    insert_dicta_en_curso($con, $id_dicta, $curso_dictado);
}

?>