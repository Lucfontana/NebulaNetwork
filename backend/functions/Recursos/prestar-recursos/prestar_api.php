<?php

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

include_once('../../../db/conexion.php');
include_once('prestar_func.php');

$con = conectar_a_bd();

session_start();

// Configurar zona horaria
date_default_timezone_set('America/Montevideo');

if (isset($_POST['prestarRecurso'])){
    
    $ci_profesor = $_POST['profesor_asignado'];
    $id_recurso = $_POST['recurso_prestado'];
    $superusuario_prestador = $_SESSION['ci'];
    $hora_prestado = date('Y-m-d H:i:s');

    //Verificar existencia de los profesores ingresados
    $existe_profesor = existe_profesor($con, $ci_profesor);
    $existe_recurso = existe_recurso($con, $id_recurso);

    //Traer la ID de la solicitud
    $id_solicita = insertar_profesor_solicita_recurso($con, $existe_profesor, $existe_recurso, $ci_profesor, $id_recurso);

    $insertar = insertar_su_presta_recurso($con, $id_solicita, $superusuario_prestador, $hora_prestado);

    echo json_encode($insertar);

}

?>