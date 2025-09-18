<?php

include ('../db/conexion.php');

if(isset($_POST['registrarDependencia'])){
    // profesor_asignado asignatura_dada hora_inicio hora_final salon_ocupado, curso_dictado
    $profesor_asignado = $_POST['profesor_asignado'];
    $asignatura_dada = $_POST['asignatura_dada'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_final = $_POST['hora_final'];
    $salon_ocupado = $_POST['salon_ocupado'];
    $curso_dictado = $_POST['curso_dictado'];
}

?>