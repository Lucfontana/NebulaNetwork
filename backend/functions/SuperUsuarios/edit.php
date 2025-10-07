<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_superusuario'];
$name = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nivelacceso = $_POST['nivelacceso'];

$consulta = "UPDATE superusuario SET nombre = ?, apellido = ?, nivel_acceso = ? WHERE id_superusuario=?"    ;
$stmt = $con->prepare($consulta);
$stmt->bind_param("ssii", $name, $apellido, $nivelacceso ,$id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Curso editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
?>
