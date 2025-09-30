<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_recurso'];
$id_espacio = $_POST['id_espacio'];
$name = $_POST['nombre'];
$estado = $_POST['estado'];
$tipo = $_POST['tipo'];

$sql = "UPDATE recursos SET id_espacio='$id_espacio', nombre='$name', descripcion='$name', tipo='$tipo', estado='$estado' WHERE id_recurso='$id'";
$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../../frontend/Recursos.php");
}
?>
