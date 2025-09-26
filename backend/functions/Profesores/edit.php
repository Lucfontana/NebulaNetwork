<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$ci = $_POST['ci_profesor'];
$name = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];   
$fecha_nac = $_POST['fnac'];
$direccion = $_POST['direccion'];

$sql = "UPDATE profesores SET nombre='$name',apellido='$apellido',email='$email',fecha_nac='$fecha_nac',direccion='$direccion' WHERE ci_profesor='$ci'";
$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../../frontend/Profesores.php");
}
?>
