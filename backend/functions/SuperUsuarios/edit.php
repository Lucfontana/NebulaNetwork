<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_superusuario'];
$name = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nivelacceso = $_POST['nivelacceso'];

$sql = "UPDATE superusuario SET nombre='$name', apellido='$apellido', nivel_acceso='$nivelacceso' WHERE id_superusuario='$id'";
$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../../frontend/SuperUsuarios.php");
}
?>
