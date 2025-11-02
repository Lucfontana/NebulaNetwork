<?php

//codigo para eliminar espacios

include_once('../../db/conexion.php');

$connect = conectar_a_bd();

$id = $_GET['id'];

//Define la consulta SQL para eliminar un registro de la tabla espacios_fisicos.
$consulta = "DELETE FROM espacios_fisicos WHERE id_espacio=?";
$stmt = $connect->prepare($consulta);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

Header("location: ../../../frontend/Mostrar_informacion.php");
//Redirige al usuario a otra página (en este caso, Mostrar_informacion.php) después de eliminar el registro.
// Es una forma de actualizar la vista tras la operación.
?>