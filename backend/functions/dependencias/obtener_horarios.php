<?php

//este codigo entrega los horarios de clases almacenados en la base de datos.

include_once("../../db/conexion.php");

$con = conectar_a_bd();

header("Content-Type: application/json");

$respuesta_json = array(); //Crea un arreglo vacío que luego contendrá la respuesta final que se enviará al usuaio

//Define una consulta SQL que selecciona todos los registros de la tabla horarios donde tipo sea 'clase',
//  ordenados por el campo hora_inicio.
$query = "SELECT * FROM horarios WHERE tipo = 'clase' ORDER BY hora_inicio";

$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$horarios = array();

//Recorre todos los resultados devueltos por la consulta con un bucle while.

//Se hace que los horarios pasen a ser un array asociativo
while($row = $result->fetch_assoc()){
    $horarios[] = $row;
    //Luego se van agregando al arreglo $horarios.
}


if (count($horarios) > 0){ //Si hay resultados, los guarda en la clave "horarios" y marca "estado" como '1' (éxito).
    $respuesta_json["horarios"] = $horarios;
    $respuesta_json["estado"] = '1';

} else { //Si no hay registros, devuelve un mensaje informativo y "estado" como '0' (fallo o sin datos).
    $respuesta_json["mensaje"] = "No hay horarios disponibles";
    $respuesta_json["estado"] = '0';
}

//Convierte el arreglo PHP ($respuesta_json) a una cadena JSON y la envía como respuesta al usuario.
echo json_encode($respuesta_json);  

//Cierra la consulta y la conexión con la base de datos para liberar recursos.
$stmt->close();
$con->close();

?>