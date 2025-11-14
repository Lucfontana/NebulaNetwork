<?php

//codigo para editar espacios

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_espacio'];
$name = $_POST['nombre'];
$capacity = $_POST['capacidad'];
$type = $_POST['tipo'];

// se prepara la consulta
$consulta = "UPDATE espacios_fisicos SET capacidad=? ,nombre=?, tipo=? WHERE id_espacio=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("issi", $capacity, $name, $type, $id);
$stmt->execute();


if ($stmt->affected_rows > 0) { // si alguna línea fue afectada
    echo json_encode(["success" => true, "message" => "Espacio editado correctamente"]);
} else { //si no
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
$stmt->close();
?>
