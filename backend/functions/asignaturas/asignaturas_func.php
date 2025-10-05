<?php

include_once('../../db/conexion.php');

$con = conectar_a_bd();

function consultar_si_existe_asignatura($con, $nombre){

$consulta = "SELECT nombre FROM asignaturas WHERE LOWER(nombre) = LOWER(?)";

$stmt = $con->prepare($consulta);
$stmt->bind_param("s", $nombre);
$stmt->execute();

$result = $stmt->get_result();

    if ($result->num_rows > 0){
        return true;
    } else {
        return false;
    }

}

function insert_datos_asignatura($con, $existe, $nombre){
    // Array para almacenar la respuesta
    $respuesta_json = array();


    if ($existe == false){
        $query_insertar = "INSERT INTO asignaturas (nombre) VALUES (?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $respuesta_json['estado'] = 1;
        $respuesta_json['mensaje'] = "Insertado correctamente";

    } else {
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Esta asignatura ya existe";
    }

    return $respuesta_json;
}


?>