<?php

include_once('../../db/conexion.php');

$con = conectar_a_bd();

//Esta función verifica si una asignatura ya existe en la tabla asignaturas.
function consultar_si_existe_asignatura($con, $nombre){

//Usa una consulta preparada, para evitar inyección SQL
//Lower compara sin importar mayúsculas/minúsculas.
$consulta = "SELECT nombre FROM asignaturas WHERE LOWER(nombre) = LOWER(?)";

$stmt = $con->prepare($consulta); // Prepara la consulta SQL y la guarda en $stmt, lista para vincular valores y ejecutar
$stmt->bind_param("s", $nombre); // Vincula el valor de $nombre al placeholder ? de la consulta, indicando que es una cadena ("s").
$stmt->execute(); // Ejecuta la consulta preparada previamente con los valores vinculados.

$result = $stmt->get_result(); // Obtiene el resultado de la consulta ejecutada y lo guarda en $result.

//Si encuentra alguna fila: devuelve true, la asignatura existe.
    if ($result->num_rows > 0){
        return true;
    } else {
        return false;
    }

}

//Esta función inserta una nueva asignatura, solo si no existe.
function insert_datos_asignatura($con, $existe, $nombre){ //$con: conexión a la BD. 
// $existe: booleano (true o false) indicando si ya existe.

    // Array para almacenar la respuesta
    $respuesta_json = array();

    if ($existe == false){
        $query_insertar = "INSERT INTO asignaturas (nombre) VALUES (?)"; //Ejecuta un INSERT en la tabla asignaturas con el nombre dado
        $stmt = $con->prepare($query_insertar); 
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        // Guarda en el arreglo que la operación fue exitosa (estado = 1)
        $respuesta_json['estado'] = 1; 

        // Agrega un mensaje descriptivo al arreglo
        $respuesta_json['mensaje'] = "Insertado correctamente"; // mensaje devuelto

    } else {
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Esta asignatura ya existe";
    }

    // Devuelve el arreglo $respuesta_json con el estado y mensaje de la operación
    return $respuesta_json;
}

?>