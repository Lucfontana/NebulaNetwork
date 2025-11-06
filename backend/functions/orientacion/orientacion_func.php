<?php

include_once('../../db/conexion.php');

$con = conectar_a_bd();

function consultar_si_existe_orientacion($con, $nombre){


    //Prepara una consulta SQL que busca si existe una orientación con el nombre ingresado(ignorando min o may con lower)
$consulta = "SELECT nombre FROM orientacion WHERE LOWER(nombre) = LOWER(?)";

$stmt = $con->prepare($consulta);
$stmt->bind_param("s", $nombre);
$stmt->execute();

$result = $stmt->get_result();

    if ($result->num_rows > 0){ //Si la consulta devuelve una o más filas, significa que ya existe ese nombre
        return true;
    } else { // si no devuelve filas, no existe
        return false;
    }

}

function insert_datos_orientacion($con, $existe, $nombre){ 
    // Array para almacenar la respuesta
    $respuesta_json = array();


    if ($existe == false){ //i $existe es falso (no existe esa orientación):
        $query_insertar = "INSERT INTO orientacion (nombre) VALUES (?)"; //Inserta una nueva fila en la tabla orientacion.
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $respuesta_json['estado'] = 1;
        $respuesta_json['mensaje'] = "Insertado correctamente";

    } else { //Si $existe es verdadero:
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Esta orientacion ya existe";
    }

    return $respuesta_json;
}


?>