<?php 
include_once('../backend/db/conexion.php');
include_once('functions.php');
include_once('../backend/queries.php');
include_once('../backend/helpers.php');

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

if (!isset($_SESSION['nivel_acceso']) && isset($_SESSION['ci'])) {
    $_GET['ci_profe'] = $_SESSION['ci'];
}

$professql = isset($_GET['ci_profe']) ? intval($_GET['ci_profe']) : 0;

// SOLO PARA TESTING - Comentar en producción
$fecha_test = '2025-10-30'; // Miércoles - Si quieren testear, cambien la fecha esta
$base_time = strtotime($fecha_test);

// Para uso actual usar esto (comentar las lineas de arriba):
// $base_time = time();

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

// Crear un arreglo para búsqueda rápida de inasistencias
// Función auxiliar para procesar resultados y agregar información de inasistencias

// Código principal simplificado

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
// 
function procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias) {
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

$query4 = query_horas_curso($con, $cursosql);

// Si se seleccionó un curso
if ($cursosql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_horas_dia_curso($con, $dia, $cursosql);
        $materias_por_dia[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
    }
}
// Si se seleccionó un espacio físico
elseif ($espaciossql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_espacios_por_dia($con, $dia, $espaciossql);
        $materias_por_dia[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
    }
}
// Si se seleccionó un profesor
elseif ($professql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_horarios_profe_pordia($con, $dia, $professql);
        $materias_por_dia[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
    }
}
// Si no se seleccionó nada (primera carga de la página)
else {
    foreach ($dias as $dia) {
        $materias_por_dia[$dia] = []; // vacío para evitar errores al recorrer luego
    }
}
?>

<title><?= t("title_schedules") ?></title>
<?php include 'nav.php'; ?>

<?php if (!isset($_SESSION['ci'])): ?>
    <body>
        <main>
            <div id="contenido-mostrar-datos">
                <h1><?= t("title_schedules") ?></h1>
                <div class="filtros"> 
                    <div id="div-curso" style="display: block;">
                        <label for="Cursos" id="select-cursos"><?= t("label_select_course") ?></label>
                        <select name="Cursos" id="cursos-select" class="cursos-select" onchange="cambiarCurso(this.value)">
                            <option value="0"><?= t("option_select_course") ?></option>
                            <?php mysqli_data_seek($query2, 0); ?>
                            <?php while ($row2 = mysqli_fetch_array($query2)): ?>
                                <option value="<?= $row2['id_curso'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $row2['id_curso']) ? 'selected' : '' ?>><?= $row2['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <?php echo cabecera_horarios()?>

                <?php if (isset($_GET['curso_id'])): ?>
                    <?php mysqli_data_seek($query4, 0); // Reset del puntero 
                    echo cargar_horarios($query4, $dias, $materias_por_dia, "nombre_asignatura");
                    ?>
                    
                <?php endif; ?>
            </div>
            </div>
        </main>
    </body>
<?php elseif (isset($_SESSION['nivel_acceso'])): ?>
    <body>
        <main>
            <div id="contenido-mostrar-datos">
                <h1><?= t("title_schedules") ?></h1>
                <div class="filtros"> <label for="horario-select"><?= t("label_select_schedule") ?></label> 
                <select id="select-horarios" name="horario-select">
                        <option value="1"><?= t("label_select_course") ?></option>
                        <option value="2"><?= t("label_select_classroom") ?></option>
                    </select>
                    <div id="div-salones" style="display: none;">
                        <label for="Salones" id="select-salones"><?= t("label_select_classroom") ?></label>
                        <select name="salones" class="salones-select" id="salones-select" onchange="cambiarEspacio(this.value)">
                            <option value="0"><?= t("option_select_classroom") ?></option>
                            <?php mysqli_data_seek($query3, 0); ?>
                            <?php while ($row3 = mysqli_fetch_array($query3)): ?>
                                <option value="<?= $row3['id_espacio'] ?>" <?= (isset($_GET['espacio_id']) && $_GET['espacio_id'] == $row3['id_espacio']) ? 'selected' : '' ?>><?= $row3['nombre'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div id="div-curso" style="display: block;">
                        <label for="Cursos" id="select-cursos"><?= t("label_select_course") ?></label>
                        <select name="Cursos" class="cursos-select" id="cursos-select" onchange="cambiarCurso(this.value)">
                            <option value="0"><?= t("option_select_course") ?></option>
                            <?php mysqli_data_seek($query2, 0); ?>
                            <?php while ($row2 = mysqli_fetch_array($query2)): ?>
                                <option value="<?= $row2['id_curso'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $row2['id_curso']) ? 'selected' : '' ?>><?= $row2['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <?php echo cabecera_horarios()?>

                <?php if (isset($_GET['curso_id'])): ?>

                    <?php echo cargar_horarios($query, $dias, $materias_por_dia, "nombre_asignatura")?>

                <?php elseif (isset($_GET['espacio_id'])): ?>

                    <?php echo cargar_horarios($query, $dias, $materias_por_dia,'nombre_espacio')?>

                <?php endif; ?>
            </div>
        </main>
    </body>
        <footer id="footer" class="footer">
            <p> &copy; <b><?= t("footer") ?></b></p>
        </footer>


        <!-- VISTA DEL PROFESOR -->
    <?php else: ?>

        <body id="body-register">
            <?php include 'nav.php'; ?>

            <main>
                <div id="contenido-mostrar-datos">
                    <h1><?= t("title_my_schedules") ?></h1>

                    <button type="button" id="Profesores-boton" class="btn" data-toggle="modal" data-target="#exampleModal">
                       <?= t("btn_register_absence") ?>
                    </button>


                    <div id="div-dialogs">
                        <div class="overlay">
                            <div class="dialogs" id="dialogs">
                                <button class="btn-Cerrar" type="button"><img class="cruz-register"
                                        src="/frontend/img/cruz.png" alt=""></button>
                                <form class="registro-div inasistencia-form">
                                    <h1><?= t("btn_register_absence") ?></h1>
                                    <hr>

                                    <div class="div-labels">
                                        <label for="dia" class="label">En el dia:</label>
                                            <input type="date" name="dia_falta" id="dia_falta" class="input-register" required>                                            
                                    </div>

                                    <div class="div-labels" id="horas_falta">
                                        <label for="nose" class="label">Cantidad de horas a faltar:</label>
                                            <input type="number" name ="cantidad_horas_falta" id="cantidad_horas_falta" class="input-register" required>                                            
                                    </div>

                                    <div class="div-labels" id="horas_clase_profe"></div>

                                    <div id="campos-dinamicos"></div>

                                    <div class="div-botones-register">
                                        <input class="btn-enviar-registro" type="submit" value="Registrar"
                                        name="registrarFalta"></input>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php echo cabecera_horarios()?>

                        <?php echo cargar_horarios($query, $dias, $materias_por_dia,"nombre_curso")?>

                </div>
            </main>
        </body>
    <?php endif; ?> <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script type="module" src="/frontend/js/prueba.js"></script>
    <script src="./js/horarios-lunes.js"></script>
    <script src="js/Register-Modal.js"></script>
    <script type='module' src="../backend/functions/Profesores/inasistencia/marcar_inasistencia.js"></script>
    <script src="js/horarios-lunes.js"></script>
    <script>
        // Función que se ejecuta cuando el usuario selecciona un curso
        // Recibe como parámetro el identificador del curso (idCurso)
        function cambiarCurso(idCurso) {

            // Verifica que el idCurso exista y que no sea igual a "0"
            // (Por ejemplo, "0" podría representar la opción "Seleccionar curso" en un <select>)
            if (idCurso && idCurso !== "0") {

                // Si la condición se cumple, redirige al usuario a la misma página
                // pero agregando el parámetro 'curso_id' en la URL.
                // Esto permite que el backend o PHP filtre los datos según el curso seleccionado.
                window.location.href = "?curso_id=" + idCurso;
            }
        }

        function cambiarEspacio(idEspacio) {

            // Verifica que el idEspacio exista y que no sea igual a "0"
            // (Por ejemplo, "0" podría representar la opción "Seleccionar curso" en un <select>)
            if (idEspacio && idEspacio !== "0") {

                // Si la condición se cumple, redirige al usuario a la misma página
                // pero agregando el parámetro 'curso_id' en la URL.
                // Esto permite que el backend o PHP filtre los datos según el curso seleccionado.
                window.location.href = "?espacio_id=" + idEspacio;
            }
        }
    </script>
    </body>