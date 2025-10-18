<?php
include_once("../../../db/conexion.php");
include_once("../../../queries.php");

session_start();
ob_clean();

header("Content-Type: application/json");
$dia;

$con = conectar_a_bd();
$respuesta_json = array();

$ci_profesor = isset($_SESSION['ci']) ? intval($_SESSION['ci']) : 0;
$horarios = array();

$dia_semana_seleccionado = isset($_POST['fecha']) ? intval($_POST['fecha']) : -1;

$dia = saber_dia_seleccionado($dia_semana_seleccionado);
// echo "Esto es lo que vale el dia: " . $dia;

if (is_null($dia)){
    $respuesta_json['dia'] = $dia;
    $respuesta_json["mensaje"] = "No hay horarios para este día; ingrese una opción válida";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
    echo json_encode($respuesta_json);
    exit;
}

if ($ci_profesor <= 0) {
    $respuesta_json["mensaje"] = "No hay sesión de profesor válida";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
    echo json_encode($respuesta_json);
    exit;
}

$query = "SELECT c.id_horario, 
                h.hora_inicio, h.hora_final,
                p.ci_profesor 
          FROM cumple c
          INNER JOIN profesor_dicta_asignatura pda ON pda.id_dicta = c.id_dicta
          INNER JOIN horarios h ON h.id_horario = c.id_horario
          INNER JOIN profesores p ON p.ci_profesor = pda.ci_profesor
          WHERE p.ci_profesor = ?
          AND c.dia = ?
          ORDER BY h.hora_inicio ASC";

$stmt = $con->prepare($query);
$stmt->bind_param("is", $ci_profesor, $dia);
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
} else {
    $respuesta_json["mensaje"] = "No hay horarios disponibles";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
}

echo json_encode($respuesta_json);
exit;
?>