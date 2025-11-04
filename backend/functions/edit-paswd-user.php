<?php
session_start();

// Header JSON al inicio
header('Content-Type: application/json');

include_once('../db/conexion.php');

$connect = conectar_a_bd();

// Validación de sesión
if (!isset($_SESSION['ci'])) {
    echo json_encode(["success" => false, "message" => "Sesión no válida"]);
    exit;
}

$ci = $_SESSION['ci'];

// Valida que lleguen los datos
if (!isset($_POST['passwd']) || !isset($_POST['newpasswd'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

$currentpasswd = $_POST['passwd'];
$newpasswd = $_POST['newpasswd'];

// Validar contraseña nueva
if (strlen($newpasswd) < 8) {
    echo json_encode(["success" => false, "message" => "La nueva contraseña debe tener al menos 8 caracteres"]);
    exit;
}

if ($currentpasswd === $newpasswd) {
    echo json_encode(["success" => false, "message" => "La nueva contraseña debe ser diferente a la actual"]);
    exit;
}

// Determinar tipo de usuario
if (!isset($_SESSION['nivel_acceso'])) {
    // PROFESOR
    // Consulta Preparada
    $consulta = "SELECT pass_profesor FROM profesores WHERE ci_profesor = ?";
    $stmt = $connect->prepare($consulta);
    $stmt->bind_param("i", $ci);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

    // Verifcia que la contraseña actual sea igual a la contraseña ingresada por el usuario
    if (!$row || !password_verify($currentpasswd, $row['pass_profesor'])) {
        echo json_encode(["success" => false, "message" => "Contraseña actual incorrecta"]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Actualizar contraseña y hasheo de contraseña
    $hash = password_hash($newpasswd, PASSWORD_DEFAULT);
    $consultaUPDATE = "UPDATE profesores SET pass_profesor = ? WHERE ci_profesor = ?";
    $stmt = $connect->prepare($consultaUPDATE);
    $stmt->bind_param("si", $hash, $ci);
    $stmt->execute();

    // Verifica que la contraseña se haya actualizado, verificando que haya alguna fila modificada
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Contraseña actualizada con éxito"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se realizaron cambios"]);
    }
    $stmt->close();

} else {
    // SUPERUSUARIO
    $consulta = "SELECT pass_superusuario FROM superUsuario WHERE id_superusuario = ?";
    $stmt = $connect->prepare($consulta);
    $stmt->bind_param("i", $ci);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

    if (!$row || !password_verify($currentpasswd, $row['pass_superusuario'])) {
        echo json_encode(["success" => false, "message" => "Contraseña actual incorrecta"]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Actualizar contraseña
    $hash = password_hash($newpasswd, PASSWORD_DEFAULT);
    $consultaUPDATE = "UPDATE superUsuario SET pass_superusuario = ? WHERE id_superusuario = ?";
    $stmt = $connect->prepare($consultaUPDATE);
    $stmt->bind_param("si", $hash, $ci);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Contraseña actualizada con éxito"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se realizaron cambios"]);
    }
    $stmt->close();
}

$connect->close();
?>