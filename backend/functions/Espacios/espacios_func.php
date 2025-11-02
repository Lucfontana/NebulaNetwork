<?php

//este cpdigo se encarga de registrar un nuevo espacio físico 

include_once ('../../db/conexion.php');

$con = conectar_a_bd();

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

//Para comentarios, ir a cursos_func.php o superusuarios_func.php
if (isset($_POST['registrarEspacio'])){

   $nombre = strip_tags(trim($_POST['name']));
   $capacity = (int)$_POST['capacity'];
   $tipo = strip_tags(trim($_POST['tipo'])); 

   //Llama a una función que busca si hay un registro con ese mismo nombre en la tabla espacios_fisicos.
   $existe = consultar_si_existe_espacio($con, $nombre);

   //Si no existe, llsms esta funcion y lo intenta registrar y luego devuelve un JSON con el resultado (éxito o error).
   $insert_espacio = insert_datos_espacios($con, $existe, $nombre, $capacity, $tipo);

   echo json_encode($insert_espacio);
}

function consultar_si_existe_espacio($con, $nombre){

//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT nombre FROM espacios_fisicos WHERE LOWER(nombre) = LOWER(?)";
//Prepara una consulta SQL para buscar un nombre

$stmt = $con->prepare($consulta);

$stmt->bind_param("s", $nombre);

$stmt->execute();

$result = $stmt->get_result();

    //Verifiac existencia y devuelve true/false
    if ($result->num_rows > 0){ //si encuentra alguna linea:
        return true;
    } else { // si no:
        return false;
    }
}

function insert_datos_espacios($con, $existe, $nombre, $capacity, $tipo){
    if ($existe == false){//Si $existe es falso (no existe) prepara una sentencia SQL para insertar el registro.

    // Array para almacenar la respuesta
    $respuesta_json = array();

        // No incluir id_espacio - es AUTO_INCREMENT
        $query_insertar = "INSERT INTO espacios_fisicos (nombre, capacidad, tipo) VALUES ( ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        
        // 3 parámetros, 3 tipos
        $stmt->bind_param("sis", $nombre, $capacity, $tipo);
        
        if ($stmt->execute()) { // si la iserccion tiene exito
            $respuesta_json['estado'] = 1;
            $respuesta_json['mensaje'] = "Insertado correctamente";

        } else {// si ocurre algun error
            echo "Error al insertar: " . $stmt->error;
        }
        
    } else { //Si ya existía
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Este curso ya existe";
    }

    return $respuesta_json; //convierte array PHP en un JSON
}

?>