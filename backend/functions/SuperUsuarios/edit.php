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
$result = $stmt->get_result();

Header("location: ../../../frontend/SuperUsuarios.php");
?>
