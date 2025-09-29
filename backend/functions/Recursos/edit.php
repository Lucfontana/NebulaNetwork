<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_recurso'];
$name = $_POST['nombre'];
$tipo = $_POST['tipo'];
$estado = $_POST['estado'];
$

$sql = "UPDATE recursos SET nombre='$name', tipo='$tipo', estado='$estado' WHERE id_recurso='$id'";
$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../../frontend/Recursos.php");
}
?>
