<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_espacio'];
$name = $_POST['nombre'];
$capacity = $_POST['capacidad'];
$type = $_POST['tipo'];


$consulta = "UPDATE espacios_fisicos SET capacidad=? ,nombre=?, tipo=? WHERE id_espacio=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("issi", $capacity, $name, $type, $id);
$stmt->execute();
$result = $stmt->get_result();

Header("location: ../../../frontend/Espacios.php");
?>
