<?php

include_once("../../../db/conexion.php");
include_once("reg_inasistencia.php");
include_once("../../../queries.php");

session_start();

$con = conectar_a_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrarFalta'])){

    //Se traen los valores que fueron enviados por marcar_inasistencia.js
    $ci_profesor = $_SESSION['ci'];
    $dia_faltar = $_POST['dia_falta'];
    $horas_faltar = $_POST['hora_profesor_da_clase'];
    $dia_semana_seleccionado = $_POST['dia_semana_seleccionada'];

    $nombre_dia_seleccionado = saber_dia_seleccionado($dia_semana_seleccionado);

    $resultado = registrar_falta_completa(
        $con,
        $ci_profesor, 
        $horas_faltar, 
        $nombre_dia_seleccionado,
        $dia_faltar
    );

    echo json_encode($resultado);

    $con->close();
}

?>