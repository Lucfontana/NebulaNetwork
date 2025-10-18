<?php

include_once ('../../db/conexion.php');

$con = conectar_a_bd();

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

//Para comentarios, ir a superusuarios_func o profesores_func
if(isset($_POST['registrarRecurso'])){
    $nombre = strip_tags(trim($_POST['name']));
    $estado = strip_tags(trim($_POST['estado']));
    $tipo = strip_tags(trim($_POST['tipo']));

    //Recibe la FK del salon
    $pertenece_a = (int)$_POST['pertenece'];

    $existe =consultar_si_existe_recurso($con, $nombre);

    $insert_recursos = insert_datos_recursos($con, $existe, $nombre, $estado, $tipo, $pertenece_a);

    error_log("Valor recibido de pertenece: " . $_POST['pertenece']);
    error_log("Tipo de dato: " . gettype($_POST['pertenece']));
    //Se codifica el resultado de la insercion como json
    echo json_encode($insert_recursos);
}

function consultar_si_existe_recurso($con, $nombre){
//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT nombre FROM recursos WHERE nombre = ?";

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

function insert_datos_recursos($con, $existe, $nombre, $estado, $tipo, $pertenece_a){
    $respuesta_json = array();

    if ($existe == false){
        
        // VALIDAR QUE EL ID_ESPACIO EXISTE (si no es NULL)
        if ($pertenece_a !== NULL) {
            $query_validar = "SELECT id_espacio FROM espacios_fisicos WHERE id_espacio = ?";
            $stmt_validar = $con->prepare($query_validar);
            $stmt_validar->bind_param("i", $pertenece_a);
            $stmt_validar->execute();
            $result_validar = $stmt_validar->get_result();
            
            if ($result_validar->num_rows == 0) {
                $respuesta_json['estado'] = 0;
                $respuesta_json['mensaje'] = "El espacio físico seleccionado no existe";
                return $respuesta_json;
            }
        }
        
        $query_insertar = "INSERT INTO recursos (id_espacio, nombre, estado, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        
        if ($pertenece_a === NULL) {
            $stmt->bind_param("isss", $pertenece_a, $nombre, $estado, $tipo);
        } else {
            $stmt->bind_param("isss", $pertenece_a, $nombre, $estado, $tipo);
        }
        
        if ($stmt->execute()) {
            $respuesta_json['estado'] = 1;
            $respuesta_json['mensaje'] = "Insertado correctamente";
        } else {
            $respuesta_json['estado'] = 0;
            $respuesta_json['mensaje'] = "Error al insertar: " . $stmt->error;
        }
        
    } else {
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Este recurso ya existe";
    }

    return $respuesta_json;
}

?>