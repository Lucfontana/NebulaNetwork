<?php 
include_once('../backend/db/conexion.php');
include_once('../backend/queries.php');

//seleccionamos los horarios para desplegarlos en forma ascendente
$connect = conectar_a_bd();

$query = query_horarios($con);

$query2 = query_cursos($con);

//seleccionamos los salones para el select
$query3 = query_espacios_sin_general($con);
session_start();

// Verificar si se ha enviado un ID de curso o de espacio a través de GET
$cursosql = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;
$espaciossql = isset($_GET['espacio_id']) ? intval($_GET['espacio_id']) : 0;

if (!isset($_SESSION['nivel_acceso']) && isset($_SESSION['ci'])) {
    $_GET['ci_profe'] = $_SESSION['ci'];
}

$professql = isset($_GET['ci_profe']) ? intval($_GET['ci_profe']) : 0;


// Arreglo con los días de la semana
$dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

// Arreglo para almacenar las materias por día
$materias_por_dia = [];


//muestra todas las horas que hay en la bd sin repetirse y dependiendo del curso
$query4 = query_horas_curso($con, $cursosql);

// Si se seleccionó un curso
if ($cursosql > 0) {
    foreach ($dias as $dia) {
        $resultado = query_horas_dia_curso($con, $dia, $cursosql);
        $materias_por_dia[$dia] = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $materias_por_dia[$dia][] = $fila;
        }
    }
}
// Si se seleccionó un espacio físico (salón, laboratorio, etc.)
elseif ($espaciossql > 0) {
    foreach ($dias as $dia) {
        $sql = "SELECT 
                    a.nombre AS nombre_asignatura,
                    e.nombre AS nombre_espacio,
                    h.hora_inicio,
                    h.hora_final,
                    h.tipo,
                    c.nombre AS nombre_curso,
                    cu.dia
                FROM cumple cu
                INNER JOIN profesor_dicta_asignatura pda ON cu.id_dicta = pda.id_dicta
                INNER JOIN asignaturas a ON pda.id_asignatura = a.id_asignatura
                INNER JOIN dicta_en_curso dc ON pda.id_dicta = dc.id_dicta
                INNER JOIN cursos c ON dc.id_curso = c.id_curso
                INNER JOIN horarios h ON cu.id_horario = h.id_horario
                INNER JOIN dicta_ocupa_espacio doe ON cu.id_dicta = doe.id_dicta
                INNER JOIN espacios_fisicos e ON doe.id_espacio = e.id_espacio
                WHERE cu.dia = '$dia'
                  AND e.id_espacio = $espaciossql
                  AND h.tipo = 'clase'
                ORDER BY h.hora_inicio ASC";

        $resultado = mysqli_query($connect, $sql);
        $materias_por_dia[$dia] = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $materias_por_dia[$dia][] = $fila;
        }
    }

    //DE ESTA QUERY DESPUES SE PUEDE SACAR: NOMBRE CURSO, NOMBRE ESPACIO
} elseif ($professql > 0) {
    foreach ($dias as $dia) {
        $sql = "SELECT 
                a.nombre AS nombre_asignatura,
                e.nombre AS nombre_espacio,
                h.hora_inicio, h.hora_final,
                c.nombre AS nombre_curso,
                cu.dia
            FROM cumple cu
            INNER JOIN profesor_dicta_asignatura pda ON cu.id_dicta = pda.id_dicta
            INNER JOIN asignaturas a ON pda.id_asignatura = a.id_asignatura
            INNER JOIN dicta_en_curso dc ON pda.id_dicta = dc.id_dicta
            INNER JOIN cursos c ON dc.id_curso = c.id_curso
            INNER JOIN horarios h ON cu.id_horario = h.id_horario
            INNER JOIN dicta_ocupa_espacio doe ON cu.id_dicta = doe.id_dicta
            INNER JOIN espacios_fisicos e ON doe.id_espacio = e.id_espacio
            INNER JOIN profesores p ON p.ci_profesor = pda.ci_profesor
            WHERE cu.dia = '$dia'
                AND p.ci_profesor = $professql
            ORDER BY h.hora_inicio ASC";

        $resultado = mysqli_query($connect, $sql);
        $materias_por_dia[$dia] = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $materias_por_dia[$dia][] = $fila;
        }
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
                        <select name="Cursos" id="cursos-select" onchange="cambiarCurso(this.value)">
                            <option value="0"><?= t("option_select_course") ?></option>
                            <?php mysqli_data_seek($query2, 0); ?>
                            <?php while ($row2 = mysqli_fetch_array($query2)): ?>
                                <option value="<?= $row2['id_curso'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $row2['id_curso']) ? 'selected' : '' ?>><?= $row2['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="datos-header">
                    <div class="datos-row">
                        <div class="horas-titulo"><?= t("label_hours") ?></div>
                        <div class="dias"><?= t("day_monday") ?></div>
                        <div class="dias"><?= t("day_tuesday") ?></div>
                        <div class="dias"><?= t("day_wednesday") ?></div>
                        <div class="dias"><?= t("day_thursday") ?></div>
                        <div class="dias"><?= t("day_friday") ?></div>
                    </div>
                </div>
                <?php if (isset($_GET['curso_id'])): ?>
                    <?php mysqli_data_seek($query4, 0); // Reset del puntero 
                    ?>
                    <div class="datos-body"> <?php while ($row = mysqli_fetch_array($query4)): ?>
                            <div class="datos-row mostrar-datos">
                                <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                                <?php foreach ($dias as $dia): ?>
                                    <?php
                                                        $mostro = false;
                                                        foreach ($materias_por_dia[$dia] as $m) {
                                                            if ($m['hora_inicio'] == $row['hora_inicio']) {
                                                                echo "<div class='dia-dato'>
                                                <strong>{$m['nombre_asignatura']}</strong>
                                              </div>";
                                                                $mostro = true;
                                                            }
                                                        }
                                                        if (!$mostro) {
                                                            echo "<div class='dia-dato'><em>---</em></div>";
                                                        }
                                    ?>
                                <?php endforeach; ?>
                            </div> <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </body>
<?php elseif (isset($_SESSION['nivel_acceso'])): ?>
    <body>
        <main>
            <div id="contenido-mostrar-datos">
                <h1><?= t("title_schedules") ?></h1>
                <div class="filtros"> <label for="horario-select"><?= t("label_select_schedule") ?></label> <select id="select-horarios"
                        name="horario-select">
                        <option value="1"><?= t("label_select_course") ?></option>
                        <option value="2"><?= t("label_select_classroom") ?></option>
                    </select>
                    <div id="div-salones" style="display: none;">
                        <label for="Salones" id="select-salones"><?= t("label_select_classroom") ?></label>
                        <select name="salones" id="salones-select" onchange="cambiarEspacio(this.value)">
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
                        <select name="Cursos" id="cursos-select" onchange="cambiarCurso(this.value)">
                            <option value="0"><?= t("option_select_course") ?></option>
                            <?php mysqli_data_seek($query2, 0); ?>
                            <?php while ($row2 = mysqli_fetch_array($query2)): ?>
                                <option value="<?= $row2['id_curso'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $row2['id_curso']) ? 'selected' : '' ?>><?= $row2['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="datos-header">
                    <div class="datos-row">
                        <div class="horas-titulo"><?= t("label_hours") ?></div>
                        <div class="dias"><?= t("day_monday") ?></div>
                        <div class="dias"><?= t("day_tuesday") ?></div>
                        <div class="dias"><?= t("day_wednesday") ?></div>
                        <div class="dias"><?= t("day_thursday") ?></div>
                        <div class="dias"><?= t("day_friday") ?></div>
                    </div>
                </div>
                <?php if (isset($_GET['curso_id'])): ?>
                    <?php mysqli_data_seek($query, 0); // Reset del puntero 
                    ?>
                    <div class="datos-body"> <?php while ($row = mysqli_fetch_array($query)): ?>
                            <div class="datos-row mostrar-datos">
                                <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                                <?php foreach ($dias as $dia): ?>
                                    <?php
                                                        $mostro = false;
                                                        foreach ($materias_por_dia[$dia] as $m) {
                                                            if ($m['hora_inicio'] == $row['hora_inicio']) {
                                                                echo "<div class='dia-dato'>
                                                <strong>{$m['nombre_asignatura']}</strong>
                                              </div>";
                                                                $mostro = true;
                                                            }
                                                        }
                                                        if (!$mostro) {
                                                            echo "<div class='dia-dato'><em>---</em></div>";
                                                        }
                                    ?>
                                <?php endforeach; ?>
                            </div> <?php endwhile; ?>
                    </div>
                <?php elseif (isset($_GET['espacio_id'])): ?>
                    <div class="datos-body"> <?php while ($row = mysqli_fetch_array($query)): ?>
                            <div class="datos-row mostrar-datos">
                                <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                                <?php foreach ($dias as $dia): ?>
                                    <?php
                                                        $mostro = false;
                                                        foreach ($materias_por_dia[$dia] as $m) {
                                                            if ($m['hora_inicio'] == $row['hora_inicio']) {
                                                                echo "<div class='dia-dato'>
                                                <strong>{$m['nombre_espacio']}</strong>
                                              </div>";
                                                                $mostro = true;
                                                            }
                                                        }
                                                        if (!$mostro) {
                                                            echo "<div class='dia-dato'><em>---</em></div>";
                                                        }
                                    ?>
                                <?php endforeach; ?>
                            </div> <?php endwhile; ?>
                    </div>
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
                                        <label for="dia" class="label"><?= t("label_day_absence") ?></label>
                                        <select class="input-register" type="text" name="dia_falta" id="dia_falta"
                                            required placeholder="Ingrese el dia">
                                            <option value=""></option>
                                            <option value="lunes"><?= t("day_monday") ?></option>
                                            <option value="martes"><?= t("day_tuesday") ?></option>
                                            <option value="miercoles"><?= t("day_wednesday") ?></option>
                                            <option value="jueves"><?= t("day_thursday") ?></option>
                                            <option value="viernes"><?= t("day_friday") ?></option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="datos-header">
                        <div class="datos-row">
                            <div class="horas-titulo"><?= t("label_hours") ?></div>
                            <div class="dias"><?= t("day_monday") ?></div>
                            <div class="dias"><?= t("day_tuesday") ?></div>
                            <div class="dias"><?= t("day_wednesday") ?></div>
                            <div class="dias"><?= t("day_thursday") ?></div>
                            <div class="dias"><?= t("day_friday") ?></div>
                        </div>
                    </div>

                    <div class="datos-body">
                        <?php mysqli_data_seek($query, 0); // Reset del puntero 
                        ?>
                        <?php while ($row = mysqli_fetch_array($query)): ?>
                            <div class="datos-row mostrar-datos">
                                <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                                <?php foreach ($dias as $dia): ?>
                                    <?php
                                    $mostro = false;
                                    foreach ($materias_por_dia[$dia] as $m) {
                                        if ($m['hora_inicio'] == $row['hora_inicio']) {
                                            echo "<div class='dia-dato'>
                                                <small>Curso: {$m['nombre_curso']} </small>
                                              </div>";
                                            $mostro = true;
                                            break; // Solo mostrar una vez por horario
                                        }
                                    }
                                    if (!$mostro && $row['tipo'] == 'recreo') {
                                        echo "<div class='dia-dato-recreo'><em>Recreo</em></div>";
                                    } else if (!$mostro) {
                                        echo "<div class='dia-dato'><em>---</em></div>";
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </main>
        </body>
    <?php endif; ?> <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script type="module" src="/frontend/js/prueba.js"></script>
    <script src="./js/horarios-lunes.js"></script>
    <script src="js/Register-Modal.js"></script>
    <script type='module' src="../backend/functions/Profesores/inasistencia/marcar_inasistencia.js"></script>
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