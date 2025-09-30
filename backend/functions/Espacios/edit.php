<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_espacio'];
$name = $_POST['nombre'];
$capacity = $_POST['capacidad'];
$equipment = $_POST['equip'];
$type = $_POST['tipo'];


$sql = "UPDATE espacios_fisicos SET capacidad='$capacity', equipamiento='$equipment' ,nombre='$name', tipo='$type' WHERE id_espacio='$id'";
$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../../frontend/Espacios.php");
}
?>
