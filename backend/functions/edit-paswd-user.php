<?php
session_start();
include_once('../db/conexion.php');
include_once('../login/login_usrs.php');
$connect = conectar_a_bd();

$ci = $_SESSION['ci'];

// Contraseñas recibidas desde el formulario
$currentpasswd = $_POST['passwd'];
$newpasswd = $_POST['newpasswd'];

// Dependiendo del tipo de usuario
if (!isset($_SESSION['nivel_acceso'])) {
    // Profesor
    $sql = "SELECT pass_profesor FROM profesores WHERE ci_profesor='$ci'";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row || !password_verify($currentpasswd, $row['pass_profesor'])) {
        echo json_encode(["success" => false, "message" => "❌ Contraseña actual incorrecta"]);
        exit;
    }

    // Nueva contraseña encriptada
    $hash = password_hash($newpasswd, PASSWORD_DEFAULT);
    $sql = "UPDATE profesores SET pass_profesor='$hash' WHERE ci_profesor='$ci'";
    $_SESSION['pass_profesor'] = $newpasswd;
} else {
    // Superusuario
    $sql = "SELECT pass_superusuario FROM superusuario WHERE id_superusuario='$ci'";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row || !password_verify($currentpasswd, $row['pass_superusuario'])) {
        echo json_encode(["success" => false, "message" => "❌ Contraseña actual incorrecta"]);
        exit;
    }

    $hash = password_hash($newpasswd, PASSWORD_DEFAULT);
    $sql = "UPDATE superusuario SET pass_superusuario='$hash' WHERE id_superusuario='$ci'";
    $_SESSION['pass_superusuario'] = $newpasswd;
}

// Ejecutar update
if (mysqli_query($connect, $sql)) {
    echo json_encode(["success" => true, "message" => "✅ Contraseña actualizada con éxito"]);
} else {
    echo json_encode(["success" => false, "message" => "⚠️ Error al actualizar"]);
}
?>