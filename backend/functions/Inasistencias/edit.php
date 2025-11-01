<?php
include_once ('../../db/conexion.php');
$connect = conectar_a_bd();

header('Content-Type: application/json');

// Validación básica
if (!isset($_POST['id_inasistencia'], $_POST['dia_falta'], $_POST['id_horario'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
    exit;
}

$id = $_POST['id_inasistencia'];
$fecha = $_POST['dia_falta'];
$horario = $_POST['id_horario'];

// Consulta preparada
$consulta = "UPDATE inasistencia 
             SET fecha_inasistencia = ?, id_horario = ?
             WHERE id_inasistencia = ?";

$stmt = $connect->prepare($consulta);
$stmt->bind_param("sii", $fecha, $horario, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Inasistencia actualizada correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "No se realizaron cambios o error al actualizar"]);
}

$stmt->close();
?>


