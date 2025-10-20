<?php
include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

$id = $_POST['id_inasistencia'];
$fecha = $_POST['fecha_inasistencia'];
$horario = $_POST['id_horario'];


$consulta = "UPDATE  SET fecha_inasistencias = ?, id_horario = ? WHERE id_inasistencia=?";
$stmt = $connect->prepare($consulta);
$stmt->bind_param("sii", $fecha, $horario ,$id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Curso editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
$stmt->close();

?>
