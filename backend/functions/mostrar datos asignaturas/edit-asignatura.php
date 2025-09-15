<?php

include_once ('../../db/conexion.php');

$connect = conectar_a_bd();

$id = $_POST['id_asignatura'];
$name = $_POST['nombre'];

$sql = "UPDATE asignaturas SET nombre='$name' WHERE id_asignatura='$id'";
$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../frontend/asignaturas.php");
}
?>
