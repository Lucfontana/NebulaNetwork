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

    $consulta = "SELECT pass_profesor FROM profesores WHERE ci_profesor=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $ci);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);
    $stmt->close();


    if (!$row || !password_verify($currentpasswd, $row['pass_profesor'])) {
        echo json_encode(["success" => false, "message" => "Contraseña actual incorrecta"]);
        exit;
    }

    // Nueva contraseña encriptada
    $hash = password_hash($newpasswd, PASSWORD_DEFAULT);
    $consultaUPDATE = "UPDATE profesores SET pass_profesor=? WHERE ci_profesor=?";
    $stmt = $con->prepare($consultaUPDATE);
    $stmt->bind_param("si", $hash, $ci);
    $stmt->execute();
    $stmt->close();

    $_SESSION['pass_profesor'] = $newpasswd;
} else {
    // Superusuario

    $consulta = "SELECT pass_superusuario FROM superusuario WHERE id_superusuario=?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $ci);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);
    $stmt->close();


    if (!$row || !password_verify($currentpasswd, $row['pass_superusuario'])) {
        echo json_encode(["success" => false, "message" => "Contraseña actual incorrecta"]);
        exit;
    }

    $hash = password_hash($newpasswd, PASSWORD_DEFAULT);
    $consultaUPDATE = "UPDATE superusuario SET pass_superusuario=? WHERE id_superusuario=?";
    $stmt = $con->prepare($consultaUPDATE);
    $stmt->bind_param("si", $hash, $ci);
    $stmt->execute();
    $stmt->close();
    $_SESSION['pass_superusuario'] = $newpasswd;
}

// Ejecutar update
if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Contraseña actualizada con éxito"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
?>