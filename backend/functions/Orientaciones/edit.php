<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_orientacion'];
$name = $_POST['nombre'];

$consulta = "UPDATE orientacion SET nombre=? WHERE id_orientacion=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("si", $name ,$id);
$stmt->execute();


if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Orientacion editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
$stmt->close();

?>
