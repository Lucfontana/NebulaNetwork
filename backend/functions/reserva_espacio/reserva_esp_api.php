<?php

//Este archivo se encarga de registrar una reserva de un espacio

include_once("../../db/conexion.php");
include_once("../../queries.php");
include_once("reservar_esp_func.php");

session_start();

$con = conectar_a_bd();

//Si petición HTTP es de tipo POST y el campo registrarReservaEspacio está en el formulario, se registra la reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrarReservaEspacio'])){

    $ci_profesor = $_SESSION['ci'];
    $fecha_reservar = $_POST['fecha_reservar'];
    $horas_reservar = $_POST['hora_profesor_da_clase'];
    $espacio_reservar = $_POST['espacio_reservar'];
    $dia_semana_seleccionado = $_POST['dia_semana_seleccionada'];

    //Esta función convierte el número del día en un nombre legible (1 → Lunes, 2 → Martes, etc.)
    $nombre_dia_seleccionado = saber_dia_seleccionado($dia_semana_seleccionado);

    //función principal que guarda toda la información en la base de datos.
    $resultado = registrar_reserva_completa(
        $con,
        $ci_profesor, 
        $fecha_reservar, 
        $horas_reservar,
        $nombre_dia_seleccionado,
        $espacio_reservar
    );

    //Convierte $resultado a formato JSON y lo envía como respuesta.
    echo json_encode($resultado);

    $con->close();
}

?>