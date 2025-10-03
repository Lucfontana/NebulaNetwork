<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_curso'];
$name = $_POST['nombre'];
$capacidad = $_POST['capacidad'];


$consulta = "UPDATE cursos SET nombre = ?, capacidad = ? WHERE id_curso=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("sii", $name, $capacidad ,$id);
$stmt->execute();
$result = $stmt->get_result();

Header("location: ../../../frontend/Cursos.php");
?>
