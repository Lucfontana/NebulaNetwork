<?php

// se importa la conexión de la bd y archivo php
include_once ('../../db/conexion.php');
include_once ('asignaturas_func.php');

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

//Llama a la función conectar_a_bd que devuelve una conexión activa a la base de datos y la guarda en $con.
$con = conectar_a_bd();

if(isset($_POST['registrarAsignatura'])){

    //se toma el valor de nombreAsignatura enviado por POST, trim quita los espacios al inicio y al final, 
    //strip_tags limina cualquier etiqueta HTML o código malicioso como medida de seguridad, y se guarda en nombre_asignatura
    $nombre_asignatura = strip_tags(trim($_POST['nombreAsignatura']));

    //Llama a una función que verifica si esa asignatura ya existe en la base de datos
    $existe = consultar_si_existe_asignatura($con, $nombre_asignatura);

    //Si no existe, se registra
    $insertar_datos = insert_datos_asignatura($con, $existe, $nombre_asignatura);

    //Convierte el resultado ($insertar_datos) a formato JSON y lo imprime (muestra el mensaje)
    echo json_encode($insertar_datos);

}

?>