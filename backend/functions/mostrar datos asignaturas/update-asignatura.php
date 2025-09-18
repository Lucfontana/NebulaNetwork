<?php
include_once('../backend/db/conexion.php');
$connect = conectar_a_bd();
$id = $_GET['id'];

$sql = "SELECT * FROM asignaturas WHERE id_asignatura='$id'";
$query = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($query)

?>