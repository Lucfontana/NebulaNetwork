<?php

include_once ('../../db/conexion.php');
include_once ('orientacion_func.php');

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

$con = conectar_a_bd();

//Este bloque se ejecuta solo si se ha enviado un formulario registrarOrientacion.
if(isset($_POST['registrarOrientacion'])){
    $nombre_asignatura = strip_tags(trim($_POST['nombreOrientacion']));
    //trim() quita espacios en blanco al inicio y final. 
    // strip_tags() elimina cualquier etiqueta HTML (por seguridad).

    //Llama a una función que busca en la base de datos si ya existe una orientación con ese nombre.
    $existe = consultar_si_existe_orientacion($con, $nombre_asignatura);

    //cllama a función que compurueba que no esta registrada, y si no lo esta, la insterta
    $insertar_orientacion = insert_datos_orientacion($con, $existe, $nombre_asignatura);

    echo json_encode($insertar_orientacion);

}

?>