<?php

include_once("../../db/conexion.php");

$con = conectar_a_bd();

header("Content-Type: application/json");

$respuesta_json = array();

$query = "SELECT * FROM horarios WHERE tipo = 'clase' ORDER BY hora_inicio";

$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$horarios = array();

//Se hace que los horarios pasen a ser un array asociativo
while($row = $result->fetch_assoc()){
    $horarios[] = $row;
}


if (count($horarios) > 0){
    $respuesta_json["horarios"] = $horarios;
    $respuesta_json["estado"] = '1';
} else {
    $respuesta_json["mensaje"] = "No hay horarios disponibles";
    $respuesta_json["estado"] = '0';
}

echo json_encode($respuesta_json);  

$stmt->close();
$con->close();


?>