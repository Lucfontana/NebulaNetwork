<?php
include_once("../../db/conexion.php");
include_once("../../queries.php");

ob_clean(); //limpia cualquier salida previa del búfer (para que no se mezclen datos).

header("Content-Type: application/json");

$con = conectar_a_bd();

$respuesta_json = array();//arrays vacios para guardar resultados
$horarios = array();

//Lectura de variables del formulario (enviadas por POST)
$dia_semana_seleccionado = intval($_POST['fecha']); //(numérica) representa el día de la semana (1=Lunes, 2=Martes…).
$espacio = intval($_POST['espacio']);//ID del lugar o aula.
$fecha_seleccionada = $_POST['fecha_seleccionada']; //fecha exacta seleccionada (por ejemplo, “2025-11-12”).

$base_time = strtotime($fecha_seleccionada);//Convierte la fecha seleccionada a un timestamp.

// Calcular inicio y fin de la semana actual (Lunes a Viernes)
$inicio_semana_str = date('Y-m-d', strtotime('monday this week', $base_time));
$fin_semana_str = date('Y-m-d', strtotime('friday this week', $base_time));

//convierte el número del día a texto
$dia = saber_dia_seleccionado($dia_semana_seleccionado);

if (is_null($dia)){ //Si el día es inválido:
    $respuesta_json['dia'] = $dia;
    $respuesta_json["mensaje"] = "No hay horarios para este día; ingrese una opción válida";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
    echo json_encode($respuesta_json);
    exit;
}

//Se tra
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
//Se buscan horarios disponibles (h) que cumplan:
//No estén ocupados por clases (dicta_ocupa_espacio).
//No estén reservados (reservas_espacios) entre lunes y viernes de esa semana.
//Pero sí se incluyan si el horario está disponible por inasistencia registrada (inasistencia + cumple).
//excluye los horarios cuyo tipo sea 'recreo'.

$stmt = $con->prepare($query);
$stmt->bind_param("isissssss", $espacio, $dia, $espacio, $dia, $inicio_semana_str, $fin_semana_str, $dia, $inicio_semana_str, $fin_semana_str,);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) { //Se guarda cada fila del resultado (cada horario disponible) en un array $horarios.
    $horarios[] = $row;
}

$stmt->close();
$con->close();

if (count($horarios) > 0) { //Si hay horarios, devuelve
    $respuesta_json["horarios"] = $horarios;
    $respuesta_json["estado"] = '1';
    $respuesta_json["mensaje"] = "Horarios cargados correctamente";
    $respuesta_json["valores"] = "Dia seleccionado: " . $dia . " ID espacio seleccionado: " . $espacio . " Fecha seleccionada: " . $fecha_seleccionada . " Inicio semana: " . $inicio_semana_str . " Fin semana: " . $fin_semana_str;

} else {//Si NO hay horarios, devuelve
    $respuesta_json["mensaje"] = "No hay horarios disponibles"; //Se crea (o actualiza) una clave llamada 
    //"mensaje" dentro del arreglo $respuesta_json y se le asigna el texto informativo "No hay horarios disponibles".
    $respuesta_json["estado"] = '0'; //Se agrega otra clave, "estado", con el valor '0'.
    $respuesta_json["horarios"] = []; //Se define que la clave "horarios" es un arreglo vacío.
}

echo json_encode($respuesta_json);
exit;
?>