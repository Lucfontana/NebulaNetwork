<?php
// IMPORTANTE: No debe haber NADA antes de esta línea, ni espacios ni saltos

include_once("../../../db/conexion.php");

session_start();

$con = conectar_a_bd();

header("Content-Type: application/json");

$respuesta_json = array();

// Initialize variables
$ci_profesor = isset($_SESSION['ci']) ? intval($_SESSION['ci']) : 0;
$asistencia = 1;
$horarios = array();

if ($ci_profesor > 0) {
    $query = "SELECT c.asistencia, c.id_horario, 
                    h.hora_inicio, h.hora_final,
                     p.ci_profesor 
              FROM cumple c
              INNER JOIN profesor_dicta_asignatura pda ON pda.id_dicta = c.id_dicta
              INNER JOIN horarios h ON h.id_horario = c.id_horario
              INNER JOIN profesores p ON p.ci_profesor = pda.ci_profesor
              WHERE p.ci_profesor = ? 
              AND c.asistencia = ?
              ORDER BY h.hora_inicio ASC";

    $stmt = $con->prepare($query);


    $stmt->bind_param("ii", $ci_profesor, $asistencia);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch datos en un array asociativo
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row;
    }


    $stmt->close();
    $con->close();
}

// Build response
if (count($horarios) > 0) {
    $respuesta_json["horarios"] = $horarios;
    $respuesta_json["estado"] = '1';
} else {
    $respuesta_json["mensaje"] = "No hay horarios disponibles";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = []; // Agregar array vacío
}

echo json_encode($respuesta_json);
exit;
?>