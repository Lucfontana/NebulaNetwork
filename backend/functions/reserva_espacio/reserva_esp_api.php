<?php

include_once("../../db/conexion.php");
include_once("../../queries.php");
include_once("reservar_esp_func.php");

session_start();

$con = conectar_a_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrarReservaEspacio'])){

    $ci_profesor = $_SESSION['ci'];
    $fecha_reservar = $_POST['fecha_reservar'];
    $horas_reservar = $_POST['hora_profesor_da_clase'];
    $espacio_reservar = $_POST['espacio_reservar'];
    $dia_semana_seleccionado = $_POST['dia_semana_seleccionada'];

    $nombre_dia_seleccionado = saber_dia_seleccionado($dia_semana_seleccionado);

    $resultado = registrar_reserva_completa(
        $con,
        $ci_profesor, 
        $fecha_reservar, 
        $horas_reservar,
        $nombre_dia_seleccionado,
        $espacio_reservar
    );

    echo json_encode($resultado);

    $con->close();
}

?>