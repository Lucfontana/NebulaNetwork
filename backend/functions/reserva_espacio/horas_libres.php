<?php
include_once("../../db/conexion.php");
include_once("../../queries.php");

ob_clean();

header("Content-Type: application/json");

$con = conectar_a_bd();
$respuesta_json = array();

$horarios = array();

$dia_semana_seleccionado = intval($_POST['fecha']);
$espacio = intval($_POST['espacio']);

$fecha_seleccionada = $_POST['fecha_seleccionada'];

$base_time = strtotime($fecha_seleccionada);

// Calcular inicio y fin de la semana actual (Lunes a Viernes)
$inicio_semana_str = date('Y-m-d', strtotime('monday this week', $base_time));
$fin_semana_str = date('Y-m-d', strtotime('friday this week', $base_time));

$dia = saber_dia_seleccionado($dia_semana_seleccionado);

if (is_null($dia)){
    $respuesta_json['dia'] = $dia;
    $respuesta_json["mensaje"] = "No hay horarios para este día; ingrese una opción válida";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
    echo json_encode($respuesta_json);
    exit;
}

$query = "SELECT 
    h.id_horario, h.hora_inicio, h.hora_final, h.tipo
FROM horarios h
    WHERE (
    (
        h.id_horario NOT IN (
            SELECT doe.id_horario
            FROM dicta_ocupa_espacio doe
            WHERE doe.id_espacio = ? AND doe.dia = ?
        )
        AND h.id_horario NOT IN ( 
            SELECT r.id_horario
            FROM reservas_espacios r
            WHERE r.id_espacio = ? AND r.dia = ? AND r.fecha_reserva BETWEEN ? AND ?
        )
        OR h.id_horario IN (
            SELECT i.id_horario
            FROM inasistencia i
            JOIN cumple c ON i.id_horario = c.id_horario
            WHERE c.dia = ? AND i.fecha_inasistencia BETWEEN ? AND ?
        )
    )
    AND h.tipo != 'recreo'
)";
$stmt = $con->prepare($query);
$stmt->bind_param("isissssss", $espacio, $dia, $espacio, $dia, $inicio_semana_str, $fin_semana_str, $dia, $inicio_semana_str, $fin_semana_str,);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $horarios[] = $row;
}

$stmt->close();
$con->close();

if (count($horarios) > 0) {
    $respuesta_json["horarios"] = $horarios;
    $respuesta_json["estado"] = '1';
    $respuesta_json["mensaje"] = "Horarios cargados correctamente";
     $respuesta_json["valores"] = "Dia seleccionado: " . $dia . " ID espacio seleccionado: " . $espacio . " Fecha seleccionada: " . $fecha_seleccionada . " Inicio semana: " . $inicio_semana_str . " Fin semana: " . $fin_semana_str;
} else {
    $respuesta_json["mensaje"] = "No hay horarios disponibles";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
}

echo json_encode($respuesta_json);
exit;
?>