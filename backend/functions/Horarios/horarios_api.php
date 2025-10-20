<?php

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

include_once('../../db/conexion.php');

include_once('horarios_test.php');
$con = conectar_a_bd();

if (isset($_POST['registroHorario'])){

    $hora_inicio = $_POST['hora_inicio'];
    $hora_final = $_POST['hora_final'];

    $existe = consultar_si_existe_horario($con);

    $registroHorarios = registrarHorarios($hora_inicio, $hora_final, $con, $existe);

    //Pasar el resultado de la insercion cono JSON
    echo json_encode($registroHorarios);
} else if (isset($_POST['eliminarTodosHorarios'])){
    $borrar = borrar_horarios($con);

    echo json_encode($borrar);
}

?>