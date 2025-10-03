<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_asignatura'];
$name = $_POST['nombre'];

$consulta = "UPDATE asignaturas SET nombre=? WHERE id_asignatura=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("si", $name ,$id);
$stmt->execute();
$result = $stmt->get_result();

Header("location: ../../../frontend/asignaturas.php");

?>
