<?php
include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_curso'];
$name = $_POST['nombre'];
$capacidad = $_POST['capacidad'];

//Esta consulta actualizará los campos nombre y capacidad del curso cuyo id_curso coincida.
$consulta = "UPDATE cursos SET nombre = ?, capacidad = ? WHERE id_curso=?"; 
$stmt = $connect->prepare($consulta);
$stmt->bind_param("sii", $name, $capacidad ,$id);//Asocia los valores PHP a los placeholders ? de la consulta.
//s string, i integrer (entero) de id y capacidad
$stmt->execute();

if ($stmt->affected_rows > 0) { //si se modificó alguna fia:
    echo json_encode(["success" => true, "message" => "Curso editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
$stmt->close();

?>
