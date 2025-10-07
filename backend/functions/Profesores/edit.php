<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$ci = $_POST['ci_profesor'];
$name = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];   
$fecha_nac = $_POST['fnac'];
$direccion = $_POST['direccion'];


$consulta = "UPDATE profesores SET nombre = ?, apellido = ?, email = ?, fecha_nac = ?, direccion = ? WHERE ci_profesor=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("sssssi", $name, $apellido, $email, $fecha_nac, $direccion, $ci);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Curso editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
?>
