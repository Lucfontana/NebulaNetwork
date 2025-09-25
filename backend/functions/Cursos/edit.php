<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_curso'];
$name = $_POST['nombre'];
$capacidad = $_POST['capacidad'];
$cupos = $_POST['cupos'];


$sql = "UPDATE cursos SET nombre='$name', capacidad='$capacidad', cupo_disponible='$cupos' WHERE id_curso='$id'";
$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../../frontend/Cursos.php");
}
?>
