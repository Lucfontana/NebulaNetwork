<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_superusuario'];
$name = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nivelacceso = $_POST['nivelacceso'];
$email = $_POST['email'];

$consulta = "UPDATE superusuario SET nombre = ?, apellido = ?, nivel_acceso = ?, email_superusuario = ? WHERE id_superusuario=?"    ;
$stmt = $con->prepare($consulta);
$stmt->bind_param("ssisi", $name, $apellido, $nivelacceso, $email ,$id);
$stmt->execute();
$stmt->close();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "SuperUsuario editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
?>
