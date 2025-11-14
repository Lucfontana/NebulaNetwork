<?php
include_once ('../../db/conexion.php');
$connect = conectar_a_bd();

//Indica que las respuestas del script serán enviadas en formato JSON, no HTML.
header('Content-Type: application/json');

// Validación básica
//Comprueba que en la petición POST se hayan enviado las tres variables necesarias
if (!isset($_POST['id_inasistencia'], $_POST['dia_falta'], $_POST['id_horario'])) {

    //Si falta alguno, devuelve un mensaje JSON de error y termina la ejecución.
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

if ($stmt->affected_rows > 0) { //si hay alguna linea cambiada, significa que se modificó al menos una fila, por lo que el cambio fue exitoso.
    echo json_encode(["success" => true, "message" => "Inasistencia actualizada correctamente"]);
} else {// sino, no hubo cambios o hubo algún error
    echo json_encode(["success" => false, "message" => "No se realizaron cambios o error al actualizar"]);
}

//Cierra la sentencia preparada para liberar recursos.
$stmt->close();
?>


