<?php

include_once('../backend/db/conexion.php');
include_once('functions.php');
include_once('../backend/queries.php');
include_once('../backend/helpers.php');

$result = query_espaciosfisicos($con);

$espacios_sin_general = query_espacios_sin_general($con);

$profesores_info = query_profesores($con);

$asignaturas_info = query_asignaturas($con);

$horarios_info = query_horarios($con);

$cursos_info = query_cursos($con);

$orientacion_info = query_orientacion($con);

//seleccionamos los horarios para desplegarlos en forma ascendente
$connect = conectar_a_bd();

$query = query_horarios($con);

$query2 = query_cursos($con);

//seleccionamos los salones para el select
$query3 = query_espacios_sin_general($con);

session_start();

// Verificar si se envio un ID de curso o de espacio a traves de GET
$cursosql = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;
$espaciossql = isset($_GET['espacio_id']) ? intval($_GET['espacio_id']) : 0;
$diasql = isset($_GET['dia_id']) ? intval($_GET['dia_id']) : 0;

if (!isset($_SESSION['nivel_acceso']) && isset($_SESSION['ci'])) {
    $_GET['ci_profe'] = $_SESSION['ci'];
}

$professql = isset($_GET['ci_profe']) ? intval($_GET['ci_profe']) : 0;

// SOLO PARA TESTING - Comentar para usar con la fecha actual
$fecha_test = '2025-11-03'; // Miércoles - Si quieren testear, cambien la fecha est
$base_time = strtotime($fecha_test);

// Para uso actual usar esto (comentar las lineas de arriba):
//$base_time = time();

// Calcular inicio y fin de la semana actual (Lunes a Viernes)
$inicio_semana_str = date('Y-m-d', strtotime('monday this week', $base_time));
$fin_semana_str = date('Y-m-d', strtotime('friday this week', $base_time));

// Arreglo con los días de la semana
$dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

// Mapeo de días en español a fechas específicas de esta semana
$dias_a_fechas = [
    'lunes' => date('Y-m-d', strtotime('monday this week', $base_time)),
    'martes' => date('Y-m-d', strtotime('tuesday this week', $base_time)),
    'miercoles' => date('Y-m-d', strtotime('wednesday this week', $base_time)),
    'jueves' => date('Y-m-d', strtotime('thursday this week', $base_time)),
    'viernes' => date('Y-m-d', strtotime('friday this week', $base_time))
];

// Arreglo para almacenar las materias por día
$materias_por_dia = [];

// Consulta para obtener inasistencias de la semana actual
$resultado_inasistencias = query_inasistencias_esta_semana($con, $inicio_semana_str, $fin_semana_str);

//Cada inasistencia se guarda en una llave unica (como una PK) y se guarda 
//en booleano (Si hay inasistencia, es true, si no, false)
$inasistencias = [];
while ($inasist = mysqli_fetch_assoc($resultado_inasistencias)) {
    $fecha = $inasist['fecha_inasistencia'];
    $ci = $inasist['ci_profesor'];
    $horario = $inasist['id_horario'];
    $key = "{$fecha}_{$ci}_{$horario}";
    $inasistencias[$key] = true;
}

//se aniade "tiene_inasistencia" como un "atributo" del resultado de la consulta.
//Si llega a haber una llave igual a la de las inasistencias, se marca la inasistencia como true
function procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias)
{
    $materias = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        //Se agarra la fecha de la semana actual (segun un dia) y se crea una "llave"
        $fecha_dia = $dias_a_fechas[$dia];
        $ci_prof = $fila['ci_profesor'] ?? 0;
        $id_hor = $fila['id_horario'] ?? 0;
        $key_inasist = "{$fecha_dia}_{$ci_prof}_{$id_hor}"; //2025-10-10_26197140_1
        $fila['tiene_inasistencia'] = isset($inasistencias[$key_inasist]); //booleano, devuelve true si hay coincidencia,si no false

        $materias[] = $fila;
    }
    return $materias;
}

function procesar_reservas_con_inasistencias($resultado_reservas, $dias_a_fechas, $inasistencias)
{
    $reservas = [];
    while ($fila = mysqli_fetch_assoc($resultado_reservas)) {
        $fecha_reserva = $fila['fecha_reserva'];
        $dia_reserva = $fila['dia'];
        
        //Si existe el dia de la reserva, y si la fecha de DIAS_A_FECHAS es igual 
        // a la fecha de la reserva (quiere decir que la reserva es de esta semana), se marca como true la inaistencia
        if (isset($dias_a_fechas[$dia_reserva]) && $dias_a_fechas[$dia_reserva] == $fecha_reserva) {
            $ci_prof = $fila['ci_profesor'] ?? 0;
            $id_hor = $fila['id_horario'] ?? 0;
            $key_inasist = "{$fecha_reserva}_{$ci_prof}_{$id_hor}";
            
            $fila['tiene_inasistencia'] = isset($inasistencias[$key_inasist]);
            $fila['es_reserva'] = true; // MARCADOR IMPORTANTE
            
            $reservas[] = $fila;
        }
    }
    return $reservas;
}
//Se obtienen las reservas  
$resultado_reservas = query_reservas_semana($con, $inicio_semana_str, $fin_semana_str);
$reservas_semana = procesar_reservas_con_inasistencias($resultado_reservas, $dias_a_fechas, $inasistencias);

// Organizar reservas por día (Se van cargando las reservas segun el dia)
$reservas_por_dia = [];
foreach ($dias as $dia) {//Se repite el ciclo por cada dia
                                //array_filter solo guarda las reservas_semana que son TRUE 
                                //(las otras las saca) y se devuelve un arreglo filtrado 
                                //con las reservas en cada dia, guardandolo en $reservas_por_dia[dia]
                                        //Arreglo usado                     funcion           usando este valor
    $reservas_por_dia[$dia] = array_filter($reservas_semana, function($r) use ($dia) {
        return $r['dia'] === $dia;
    });
}

//Las siguientes lineas filtran la informacion y las unen para mostrarla ordenadamente
$query4 = query_horas_curso($con, $cursosql);
// Si se seleccionó un curso
if ($cursosql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_horas_dia_curso($con, $dia, $cursosql);

        //Clases_regulares tiene todos los horarios con las inasistencias marcadas
        $clases_regulares = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
        
        //Se agarran todas las reservas que hay, pero solamente se mantienen las reservas que pertenecen al curso actual 
                                    //Arreglo analizado: reservas_por_dia    se analiza segun cursosql
        $reservas_curso = array_filter($reservas_por_dia[$dia], function($r) use ($cursosql) {
            return $r['id_curso'] == $cursosql; //Se devuelve la id_curso en el que hay una reserva
        });
        
        //Eliminar clases regulares si el profesor tiene reserva en otro espacio
        //Esta parte se asegura de que un profesor no pueda estar en dos clases al mismo 
        //tiempo, al "eliminar" la clase normal en la que tendria clases 
        $clases_filtradas = array_filter($clases_regulares, function($clase) use ($reservas_curso) {
            foreach ($reservas_curso as $reserva) {
                // Si el profesor tiene una reserva al mismo tiempo que da clases pero en un salon distinto...
                if ($reserva['ci_profesor'] === $clase['ci_profesor'] &&
                    $reserva['id_horario'] === $clase['id_horario'] &&
                    $reserva['id_espacio'] !== $clase['id_espacio']) { // Diferente espacio
                    return false; // No mostrar la clase regular del espacio original
                }
            }
            return true;
        });
        
        // COMBINAR clases filtradas + reservas del curso (las reservas van primero por prioridad)
        
        //Se combinan los dos arreglos en uno solo. Como las clases ya se filtraron para que no 
        //esten duplicadas, solamente se combinan estos dos arreglos dando como prioridad las 
        //reservas_curso que tiene que ser guardado primero, y despues las clases filtradas
        $materias_por_dia[$dia] = array_merge(
            array_values($reservas_curso), 
            $clases_filtradas
        );
        $materias_por_dia_celu[$dia] = $materias_por_dia[$dia];
    }
}
// Si se seleccionó un espacio físico
elseif ($espaciossql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_espacios_por_dia($con, $dia, $espaciossql);
        $clases_regulares = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
        
        // Filtrar reservas de este espacio
        $reservas_espacio = array_filter($reservas_por_dia[$dia], function($r) use ($espaciossql) {
            return $r['id_espacio'] == $espaciossql;
        });
        
        // NUEVA LÓGICA: Eliminar clases regulares si el profesor tiene reserva en otro lado
        $clases_filtradas = array_filter($clases_regulares, function($clase) use ($reservas_semana, $dia) {
            // Buscar si este profesor tiene una reserva en el mismo día y horario
            foreach ($reservas_semana as $reserva) {
                if ($reserva['dia'] === $dia &&
                    $reserva['ci_profesor'] === $clase['ci_profesor'] &&
                    $reserva['id_horario'] === $clase['id_horario']) {
                    // El profesor tiene reserva en otro lado, liberar este espacio
                    return false;
                }
            }
            return true; // No hay reserva, mantener la clase regular
        });
        
        $materias_por_dia[$dia] = array_merge(
            array_values($reservas_espacio), 
            $clases_filtradas
        );
        $materias_por_dia_celu[$dia] = $materias_por_dia[$dia];
    }
}
// Si se seleccionó un profesor
elseif ($professql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_horarios_profe_pordia($con, $dia, $professql);
        $clases_regulares = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
        
        // Filtrar reservas de este profesor
        $reservas_profesor = array_filter($reservas_por_dia[$dia], function($r) use ($professql) {
            return $r['ci_profesor'] == $professql;
        });
        
        // NUEVA LÓGICA: Eliminar clases regulares donde tiene reserva en otro espacio
        $clases_filtradas = array_filter($clases_regulares, function($clase) use ($reservas_profesor) {
            foreach ($reservas_profesor as $reserva) {
                // Si tiene reserva a la misma hora pero en otro espacio
                if ($reserva['id_horario'] === $clase['id_horario'] &&
                    $reserva['id_espacio'] !== $clase['id_espacio']) {
                    return false; // No mostrar la clase del espacio original
                }
            }
            return true;
        });
        
        $materias_por_dia[$dia] = array_merge(
            array_values($reservas_profesor), 
            $clases_filtradas
        );
        $materias_por_dia_celu[$dia] = $materias_por_dia[$dia];
    }
}
// Si no se seleccionó nada (primera carga de la página)
else {
    foreach ($dias as $dia) {
        $materias_por_dia[$dia] = [];
        $materias_por_dia_celu[$dia] = [];
    }
}

?>