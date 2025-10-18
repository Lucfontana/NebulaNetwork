<?php
include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_curso'];
$name = $_POST['nombre'];
$capacidad = $_POST['capacidad'];


$consulta = "UPDATE cursos SET nombre = ?, capacidad = ? WHERE id_curso=?";
$stmt = $connect->prepare($consulta);
$stmt->bind_param("sii", $name, $capacidad ,$id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Curso editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
$stmt->close();

?>
