<?php
session_start();
include_once ('../login/login_usrs.php');
include_once ('../db/conexion.php');
$connect = conectar_a_bd();

$_SESSION['nombre_usuario'] = $_POST['nombre'];
$name = $_POST['nombre'];
$ci = $_SESSION['ci'];


if (!isset($_SESSION['nivel_acceso'])): {
    $sql = "UPDATE profesores SET nombre='$name' WHERE ci_profesor='$ci'";
} elseif (isset($_SESSION['nivel_acceso'])): {
    $sql = "UPDATE superusuario SET nombre='$name' WHERE id_superusuario='$ci'";
} endif;

$query = mysqli_query($connect, $sql);

if ($query) {
    Header("location: ../../../frontend/Perfil.php");
}

?>