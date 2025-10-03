<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_recurso'];
$id_espacio = $_POST['id_espacio'];
$name = $_POST['nombre'];
$estado = $_POST['estado'];
$tipo = $_POST['tipo'];

$consulta = "UPDATE reursos SET nombre = ?, descripcion = ?, tipo = ?, estado = ? WHERE id_recurso=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("ssssi", $name, $descripcion, $tipo, $estado, $id);
$stmt->execute();
$result = $stmt->get_result();

Header("location: ../../../frontend/Recursos.php");
?>
