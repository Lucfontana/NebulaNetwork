<?php

include_once ('../db/conexion.php');

$con = conectar_a_bd();

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

//Para comentarios, ir a cursos_func.php o superusuarios_func.php
if (isset($_POST['registrarEspacio'])){

   $nombre = strip_tags(trim($_POST['name']));
   $capacity = (int)$_POST['capacity'];
   $tipo = strip_tags(trim($_POST['tipo'])); 

   $existe = consultar_si_existe_espacio($con, $nombre);

   $insert_espacio = insert_datos_espacios($con, $existe, $nombre, $capacity, $tipo);

   json_encode($insert_espacio);
}

function consultar_si_existe_espacio($con, $nombre){
//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT nombre FROM espacios_fisicos WHERE LOWER(nombre) = LOWER(?)";

$stmt = $con->prepare($consulta);

$stmt->bind_param("s", $nombre);

$stmt->execute();

$result = $stmt->get_result();

    //Verifiac existencia y devuelve true/false
    if ($result->num_rows > 0){
        return true;
    } else {
        return false;
    }
}

function insert_datos_espacios($con, $existe, $nombre, $capacity, $tipo){
    if ($existe == false){
    // Array para almacenar la respuesta
    $respuesta_json = array();

        // No incluir id_espacio - es AUTO_INCREMENT
        $query_insertar = "INSERT INTO espacios_fisicos (nombre, capacidad, tipo) VALUES ( ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        
        // 3 parámetros, 3 tipos
        $stmt->bind_param("sis", $nombre, $capacity, $tipo);
        
        if ($stmt->execute()) {
            $respuesta_json['estado'] = 1;
            $respuesta_json['mensaje'] = "Insertado correctamente";
        } else {
            echo "Error al insertar: " . $stmt->error;
        }
        
    } else {
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Este curso ya existe";
    }

    return $respuesta_json;
}

?>