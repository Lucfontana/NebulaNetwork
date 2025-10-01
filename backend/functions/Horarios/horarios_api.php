<?php

include_once('../../db/conexion.php');

include_once('horarios_func.php');
$con = conectar_a_bd();

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

if (isset($_POST['registroHorario'])){

    $hora_inicio = $_POST['hora_inicio'];
    $hora_final = $_POST['hora_final'];
    $tipo_horario = $_POST['tipo_horario'];

    $existe = consultar_si_existe_horario($con, $hora_inicio);
    
    $insert_horarios = insert_datos_horas($con, $existe, $hora_inicio, $hora_final, $tipo_horario);

    //Pasar el resultado de la insercion cono JSON
    echo json_encode($insert_horarios);
}

?>