<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_asignatura'];
$name = $_POST['nombre'];

$consulta = "UPDATE asignaturas SET nombre=? WHERE id_asignatura=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("si", $name ,$id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Curso editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}

?>
