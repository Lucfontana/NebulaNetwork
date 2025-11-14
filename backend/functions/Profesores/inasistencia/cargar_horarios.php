<?php

//Este código recibe desde el frontend (por método POST) un número que representa un día de la semana 
//(fecha), y usando la sesión del profesor actualmente conectado, consulta en la base de datos los 
//horarios que tiene ese profesor en ese día.

include_once("../../../db/conexion.php");
include_once("../../../queries.php");

//Inicia o reanuda una sesión de PHP.
session_start();

//Limpia (borra) el búfer de salida actual de PHP.
ob_clean();

header("Content-Type: application/json");
$dia;

$con = conectar_a_bd();
$respuesta_json = array();

//Obtiene el CI del profesor guardado en la sesión. Si no existe, se pone 0 (valor inválido).
$ci_profesor = isset($_SESSION['ci']) ? intval($_SESSION['ci']) : 0;
$horarios = array();

//Toma el día seleccionado que viene desde el formulario por POST.
$dia_semana_seleccionado = isset($_POST['fecha']) ? intval($_POST['fecha']) : -1;
//intval convierte el valor enviado en un número entero
// -1 Valor por defecto si no se envió fecha

//Llama a una función que convierte el número en nombre del día.
$dia = saber_dia_seleccionado($dia_semana_seleccionado);
// echo "Esto es lo que vale el dia: " . $dia;


//Si el día no es válido (la función devolvió null), se envía un JSON indicando el error y se detiene el script.
if (is_null($dia)){ 
    $respuesta_json['dia'] = $dia;
    $respuesta_json["mensaje"] = "No hay horarios para este día; ingrese una opción válida";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
    echo json_encode($respuesta_json);
    exit;
}

//Si no hay sesión de profesor(ci), también se devuelve un mensaje de error.
if ($ci_profesor <= 0) {
    $respuesta_json["mensaje"] = "No hay sesión de profesor válida";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
    echo json_encode($respuesta_json);
    exit;
}


//Consulta los horarios del profesor (ci_profesor) en el día específico (dia).
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


//Recorre cada fila devuelta por la consulta y la guarda en un arreglo $horarios que contendrá todos 
// los horarios de ese profesor en ese dia.
while ($row = $result->fetch_assoc()) {
    $horarios[] = $row;
}
// $result->fetch_assoc() obtiene una fila como arreglo asociativo
//$row	Contiene los valores de una sola fila
//$horarios[] = $row;	Agrega esa fila al arreglo $horarios



$stmt->close();
$con->close();

if (count($horarios) > 0) { //Si hay resultados, indica éxito (estado = 1) y devuelve los horarios.
    $respuesta_json["horarios"] = $horarios;
    $respuesta_json["estado"] = '1';
    $respuesta_json["mensaje"] = "Horarios cargados correctamente";
} else { //Si no hay ninguno, muestra mensaje de error.
    $respuesta_json["mensaje"] = "No hay horarios disponibles";
    $respuesta_json["estado"] = '0';
    $respuesta_json["horarios"] = [];
}

//envía la respuesta al frontend
echo json_encode($respuesta_json);
exit;
?>