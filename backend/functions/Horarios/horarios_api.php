<?php

include_once('../../db/conexion.php');

include_once('horarios_func.php');
$con = conectar_a_bd();

if (isset($_POST['registroHorario'])){

    $hora_inicio = $_POST['hora_inicio'];
    $hora_final = $_POST['hora_final'];
    $tipo_horario = $_POST['tipo_horario'];

    $existe = consultar_si_existe_horario($con, $hora_inicio);
    
    insert_datos_horas($con, $existe, $hora_inicio, $hora_final, $tipo_horario);
}

?>