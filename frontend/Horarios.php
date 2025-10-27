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
$fecha_test = '2025-10-27'; // Miércoles - Si quieren testear, cambien la fecha esta
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

$query4 = query_horas_curso($con, $cursosql);

// Si se seleccionó un curso
if ($cursosql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_horas_dia_curso($con, $dia, $cursosql);
        $materias_por_dia[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
        $materias_por_dia_celu[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
    }
}
// Si se seleccionó un espacio físico
elseif ($espaciossql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_espacios_por_dia($con, $dia, $espaciossql);
        $materias_por_dia[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
        $materias_por_dia_celu[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
    }
}
// Si se seleccionó un profesor
elseif ($professql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_horarios_profe_pordia($con, $dia, $professql);
        $materias_por_dia[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
        $materias_por_dia_celu[$dia] = procesar_horarios_con_inasistencias($resultado, $dia, $dias_a_fechas, $inasistencias);
    }
}
// Si no se seleccionó nada (primera carga de la página)
else {
    foreach ($dias as $dia) {
        $materias_por_dia[$dia] = []; // vacío para evitar errores al recorrer luego
        $materias_por_dia_celu[$dia] = []; // vacío para evitar errores al recorrer luego
    }
}
?>

<title><?= t("title_schedules") ?></title>
<?php include 'nav.php'; ?>
<style>
    .dia-dato{
        display: flex;
        flex-direction: column;
    }
</style>

<?php if (!isset($_SESSION['ci'])): ?>
<!-- Vista para Alumnos -->
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
                <!-- Computadora -->
                <div class="computadora">
                    <?php echo cabecera_horarios() ?>

                    <?php if (isset($_GET['curso_id'])): ?>
                        <?php echo cargar_horarios($query4, $dias, $materias_por_dia, "nombre_asignatura", "nombre_profesor");
                        ?>
                    <?php endif; ?>
                </div>

                <!-- Vista para celular -->
                <div class="celular">
                    <?php
                    // Muestra el título "Horas" y un menú desplegable (<select>) con los días de la semana.
                    echo cabecera_horarios_celular();
                    ?>
                    <div id="contenedor-horarios-celular">
                        <?php foreach ($dias as $dia_celu): // Bucle que recorre cada día de la semana 
                        ?>
                            <!-- Cada div representa los horarios correspondientes a un día específico.
                                Solo el div del lunes se muestra por defecto, los demás se ocultan con display:none. -->
                            <div class="horario-dia" id="horario-<?= $dia_celu ?>" style="<?= $dia_celu === 'lunes' ? '' : 'display:none;' ?>">
                                <?php if (isset($_GET['curso_id'])): ?>
                                    <?php echo cargar_horarios($query4, $dias, $materias_por_dia, "nombre_asignatura", "nombre_profesor");
                                    ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>


            </div>
        </main>
    </body>
<?php elseif (isset($_SESSION['nivel_acceso'])): ?>
<!-- Vista para adscripta -->
    <body id="body-register">
        <main>
            <div id="contenido-mostrar-datos">
                <div class="div-mostrar-datos">
                    <h1><?= t("title_schedules") ?></h1>
                    <div class="botones-register-horarios">
                        <button class="btn btn-register-horario" data-toggle="modal">
                            Agregar Horas
                        </button>
                        <button class="btn btn-register-horario" data-toggle="modal">
                            Agregar Horarios
                        </button>
                    </div>
                </div>
                <div class="filtros">
                    <div class="filtro">
                        <label for="horario-select"><?= t("label_select_schedule") ?></label>
                        <select id="select-horarios" name="horario-select">
                            <option value="1"><?= t("label_select_course") ?></option>
                            <option value="2"><?= t("label_select_classroom") ?></option>
                        </select>
                    </div>
                    <div id="div-salones" style="display: none;">
                        <label for="Salones" id="select-salones"><?= t("label_select_classroom") ?></label>
                        <select name="salones" class="salones-select" id="salones-select"
                            onchange="cambiarEspacio(this.value)">
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


                <!-- Vista para computadora -->
                <div class="computadora">
                    <?php echo cabecera_horarios() ?>

                    <?php if (isset($_GET['curso_id'])): ?>
                        <?php echo cargar_horarios($query, $dias, $materias_por_dia, "nombre_asignatura", "nombre_profesor") ?>
                    <?php elseif (isset($_GET['espacio_id'])): ?>
                        <?php echo cargar_horarios($query, $dias, $materias_por_dia, 'nombre_espacio', "nombre_profesor") ?>
                    <?php endif; ?>
                </div>

                <!-- Vista para celular -->
                <div class="celular">
                    <?php
                    // Muestra el título "Horas" y un menú desplegable (<select>) con los días de la semana.
                    echo cabecera_horarios_celular();
                    ?>
                    <div id="contenedor-horarios-celular">
                        <?php foreach ($dias as $dia_celu): // Bucle que recorre cada día de la semana 
                        ?>
                            <!-- Cada div representa los horarios correspondientes a un día específico.
                                Solo el div del lunes se muestra por defecto, los demás se ocultan con display:none. -->
                            <div class="horario-dia" id="horario-<?= $dia_celu ?>" style="<?= $dia_celu === 'lunes' ? '' : 'display:none;' ?>">
                                <?php
                                // - [$dia_celu]: día actual dentro de un array (por ejemplo ['martes']).
                                if (isset($_GET['curso_id'])) {
                                    // Si se seleccionó un curso, se muestran las asignaturas por día.
                                    echo cargar_horarios($query, [$dia_celu], $materias_por_dia, "nombre_asignatura", "nombre_profesor");
                                } elseif (isset($_GET['espacio_id'])) {
                                    // Si se seleccionó un espacio físico (salón), se muestran los horarios de ese salón.
                                    echo cargar_horarios($query, [$dia_celu], $materias_por_dia, 'nombre_espacio', "nombre_profesor");
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>



                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div horarios-form">
                            <h1><?= t("header_schedules") ?></h1>
                            <hr>
                            <div class="div-labels">
                                <label for="hora_inicio" class="label"><?= t("label_start_time") ?></label>
                                <input class="input-register" type="time" name="hora_inicio" id="horaInicioHorario"
                                    maxlength="20" minlength="8" required placeholder="<?= t("placeholder_start_time") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="hora_final" class="label"><?= t("label_end_time") ?></label>
                                <input class="input-register" type="time" name="hora_final" id="horaFinalHorario" maxlength="20"
                                    minlength="8" required placeholder="<?= t("placeholder_end_time") ?>">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                    name="registroHorario"></input>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div dependencias-form"
                            action="../backend/functions/dependencias/dependencias_api.php" method="POST">
                            <h1><?= t("header_dependencies") ?></h1>
                            <hr>

                            <div class="div-labels">
                                <label for="profesor_asignado" class="label"><?= t("label_teacher") ?></label>
                                <select name="profesor_asignado" id="profesor_asignado" type="text" class="input-register">
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($profesores_info)): ?>
                                        <option value="<?= $row['ci_profesor'] ?>">
                                            <?= $row['nombre'] ?>
                                            <?= $row['apellido'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-labels">
                                <label for="asignatura_dada" class="label"><?= t("label_subject_taught") ?></label>
                                <select name="asignatura_dada" id="asignatura_dada" type="text" class="input-register">
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($asignaturas_info)): ?>
                                        <option value="<?= $row['id_asignatura'] ?>">
                                            <?= $row['nombre'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-labels">
                                <label for="dia" class="label"><?= t("label_day") ?></label>
                                <select class="input-register" type="text" name="dia_dictado" id="dia_dictado" required
                                    placeholder="<?= t("placeholder_day") ?>">
                                    <option value=""></option>
                                    <option value="lunes"><?= t("day_monday") ?></option>
                                    <option value="martes"><?= t("day_tuesday") ?></option>
                                    <option value="miercoles"><?= t("day_wednesday") ?></option>
                                    <option value="jueves"><?= t("day_thursday") ?></option>
                                    <option value="viernes"><?= t("day_friday") ?></option>
                                </select>
                            </div>

                            <div class="div-labels">
                                <label for="capacity" class="label"><?= t("label_hours_taught") ?></label>
                                <input class="input-register" type="number" id="crear_campos" maxlength="3" minlength="1"
                                    required>
                            </div>

                            <div id="campos-dinamicos"></div>

                            <div class="div-labels">
                                <label for="salon_ocupado" class="label"><?= t("label_room_used") ?></label>
                                <select name="salon_ocupado" id="salon_a_ocupar" type="text" class="input-register" required>
                                    <option value=""></option>
                                    <?php
                                    mysqli_data_seek($espacios_sin_general, 0); //Reinicia el while para que empiece de cero otra vez (el anterior while que utilizo los recursos lo dejo en el final)
                                    while ($row = mysqli_fetch_array($espacios_sin_general)): ?>
                                        <option value="<?= $row['id_espacio'] ?>">
                                            <?= $row['nombre'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-labels">
                                <label for="curso_dictado" class="label"><?= t("label_course_taught") ?></label>
                                <select name="curso_dictado" id="curso_dictado" type="text" class="input-register">
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($cursos_info)): ?>
                                        <option value="<?= $row['id_curso'] ?>">
                                            <?= $row['nombre'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                    name="registrarDependencia"></input>
                            </div>
                        </form>
                    </div>
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

                <button type="button" id="Profesores-boton" class="btn-primary btn" data-toggle="modal"
                    data-target="#exampleModal">
                    <?= t("btn_register_absence") ?>
                </button>


                <div id="div-dialogs">
                    <div class="overlay">
                        <div class="dialogs" id="dialogs">
                            <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                    alt=""></button>
                            <form class="registro-div inasistencia-form">
                                <h1><?= t("btn_register_absence") ?></h1>
                                <hr>

                                <div class="div-labels">
                                    <label for="dia" class="label">En el dia:</label>
                                    <input type="date" name="dia_falta" id="dia_falta" class="input-register" required>
                                </div>

                                <div class="div-labels" id="horas_falta">
                                    <label for="nose" class="label">Cantidad de horas a faltar:</label>
                                    <input type="number" name="cantidad_horas_falta" id="cantidad_horas_falta"
                                        class="input-register" required>
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

                <div class="computadora">
                    <?php echo cabecera_horarios() ?>

                    <?php echo cargar_horarios($query, $dias, $materias_por_dia, "nombre_curso", "nombre_espacio") ?>
                </div>

                <!-- Vista para celular -->
                <div class="celular">
                    <?php
                    // Muestra el título "Horas" y un menú desplegable (<select>) con los días de la semana.
                    echo cabecera_horarios_celular();
                    ?>
                    <div id="contenedor-horarios-celular">
                        <?php foreach ($dias as $dia_celu): // Bucle que recorre cada día de la semana 
                        ?>
                            <!-- Cada div representa los horarios correspondientes a un día específico.
                                Solo el div del lunes se muestra por defecto, los demás se ocultan con display:none. -->
                            <div class="horario-dia" id="horario-<?= $dia_celu ?>" style="<?= $dia_celu === 'lunes' ? '' : 'display:none;' ?>">
                                <?php
                                echo cargar_horarios($query, [$dia_celu], $materias_por_dia, "nombre_curso", "nombre_espacio");
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>


            </div>
        </main>
    </body>
<?php endif; ?> <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
<script type="module" src="/frontend/js/prueba.js"></script>
<script src="./js/horarios-lunes.js"></script>
<script src="js/Register-Modal.js"></script>
<script type='module' src="../backend/functions/Profesores/inasistencia/marcar_inasistencia.js"></script>
<script src="js/horarios-lunes.js"></script>

<!-- Registros -->
<script type="module" src="../backend/functions/dependencias/crear_campos.js"></script>
<script type="module" src="js/validaciones-registro.js" defer></script>
<script type="module" src="js/swalerts.js"></script>
<script src="./js/select-dia-celular.js"></script>
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