<?php

//código para registrar el préstamo de un recurso

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

include_once('../../../db/conexion.php');
include_once('prestar_func.php');

$con = conectar_a_bd();

session_start();
//Inicia o continúa la sesión actual.
//Esto permite acceder a variables como $_SESSION['ci'], que representa el usuario logueado 
// (en este caso, el superusuario que presta el recurso).

// Configurar zona horaria
date_default_timezone_set('America/Montevideo');

if (isset($_POST['prestarRecurso'])){
    
    $ci_profesor = $_POST['profesor_asignado'];
    $id_recurso = $_POST['recurso_prestado'];
    $superusuario_prestador = $_SESSION['ci'];
    $hora_prestado = date('Y-m-d H:i:s'); //fecha y hora actuales

    //Estas funciones verifican si el profesor y el recurso existen en la base de datos antes de continuar.
    $existe_profesor = existe_profesor($con, $ci_profesor);
    $existe_recurso = existe_recurso($con, $id_recurso);

    //Guarda en la base que el profesor ha solicitado el recurso, y devuelve el ID de esa solicitud
    $id_solicita = insertar_profesor_solicita_recurso($con, $existe_profesor, $existe_recurso, $ci_profesor, $id_recurso);

    //Registra que el superusuario prestó ese recurso al profesor, en una fecha y hora determinadas.
    $insertar = insertar_su_presta_recurso($con, $id_solicita, $superusuario_prestador, $hora_prestado);

    echo json_encode($insertar);

}

?>