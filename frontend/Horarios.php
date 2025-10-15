<?php include_once('../backend/db/conexion.php');
//seleccionamos los horarios para desplegarlos en forma ascendente
$connect = conectar_a_bd();
$sql = "SELECT * FROM horarios WHERE tipo = 'clase'";
$stmt = $con->prepare($sql);
$stmt->execute();
$query = $stmt->get_result();
//seleccionamos los cursos para el select
$sql2 = "SELECT * FROM cursos";
$stmt = $con->prepare($sql2);
$stmt->execute();
$query2 = $stmt->get_result();
//seleccionamos los salones para el select
$sql3 = "SELECT * FROM espacios_fisicos WHERE nombre != 'general'";
$stmt = $con->prepare($sql3);
$stmt->execute();
$query3 = $stmt->get_result();
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
$sql4 = "SELECT DISTINCT
                    h.hora_inicio,
                    h.hora_final
                FROM cumple cu
                INNER JOIN profesor_dicta_asignatura pda ON cu.id_dicta = pda.id_dicta
                INNER JOIN asignaturas a ON pda.id_asignatura = a.id_asignatura
                INNER JOIN dicta_en_curso dc ON pda.id_dicta = dc.id_dicta
                INNER JOIN cursos c ON dc.id_curso = c.id_curso
                INNER JOIN horarios h ON cu.id_horario = h.id_horario
                INNER JOIN dicta_ocupa_espacio doe ON cu.id_dicta = doe.id_dicta
                INNER JOIN espacios_fisicos e ON doe.id_espacio = e.id_espacio
                WHERE c.id_curso = $cursosql
                  AND h.tipo = 'clase'
                ORDER BY h.hora_inicio ASC";
$stmt = $con->prepare($sql4);
$stmt->execute();
$query4 = $stmt->get_result();;

// Si se seleccionó un curso
if ($cursosql > 0) {
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
                  AND c.id_curso = $cursosql
                  AND h.tipo = 'clase'
                ORDER BY h.hora_inicio ASC";


        $stmt = $con->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
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
<!DOCTYPE html>
<html lang="en">
<title>Horarios</title>
<?php include 'nav.php'; ?>
<?php if (!isset($_SESSION['ci'])): ?>
    <body>
        <main>
            <div id="contenido-mostrar-datos">
                <h1>Horarios</h1>
                <div class="filtros"> 
                    <div id="div-curso" style="display: block;">
                        <label for="Cursos" id="select-cursos">Seleccionar Curso:</label>
                        <select name="Cursos" id="cursos-select" onchange="cambiarCurso(this.value)">
                            <option value="0">Seleccione un curso</option>
                            <?php mysqli_data_seek($query2, 0); ?>
                            <?php while ($row2 = mysqli_fetch_array($query2)): ?>
                                <option value="<?= $row2['id_curso'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $row2['id_curso']) ? 'selected' : '' ?>><?= $row2['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="datos-header">
                    <div class="datos-row">
                        <div class="horas-titulo">Horas</div>
                        <div class="dias">Lunes</div>
                        <div class="dias">Martes</div>
                        <div class="dias">Miércoles</div>
                        <div class="dias">Jueves</div>
                        <div class="dias">Viernes</div>
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
                <h1>Horarios</h1>
                <div class="filtros"> <label for="horario-select">Seleccionar Horario:</label> <select id="select-horarios"
                        name="horario-select">
                        <option value="1">Cursos</option>
                        <option value="2">Salones</option>
                    </select>
                    <div id="div-salones" style="display: none;">
                        <label for="Salones" id="select-salones">Seleccionar Salon:</label>
                        <select name="salones" id="salones-select" onchange="cambiarEspacio(this.value)">
                            <option value="0">Seleccione un salón</option>
                            <?php mysqli_data_seek($query3, 0); ?>
                            <?php while ($row3 = mysqli_fetch_array($query3)): ?>
                                <option value="<?= $row3['id_espacio'] ?>" <?= (isset($_GET['espacio_id']) && $_GET['espacio_id'] == $row3['id_espacio']) ? 'selected' : '' ?>><?= $row3['nombre'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div id="div-curso" style="display: block;">
                        <label for="Cursos" id="select-cursos">Seleccionar Curso:</label>
                        <select name="Cursos" id="cursos-select" onchange="cambiarCurso(this.value)">
                            <option value="0">Seleccione un curso</option>
                            <?php mysqli_data_seek($query2, 0); ?>
                            <?php while ($row2 = mysqli_fetch_array($query2)): ?>
                                <option value="<?= $row2['id_curso'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $row2['id_curso']) ? 'selected' : '' ?>><?= $row2['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="datos-header">
                    <div class="datos-row">
                        <div class="horas-titulo">Horas</div>
                        <div class="dias">Lunes</div>
                        <div class="dias">Martes</div>
                        <div class="dias">Miércoles</div>
                        <div class="dias">Jueves</div>
                        <div class="dias">Viernes</div>
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
            <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
        </footer>


        <!-- VISTA DEL PROFESOR -->
    <?php else: ?>
        <body>
            <main>
                <div id="contenido-mostrar-datos">
                    <h1>Mis horarios</h1>
                    <div class="datos-header">
                        <div class="datos-row">
                            <div class="horas-titulo">Horas</div>
                            <div class="dias">Lunes</div>
                            <div class="dias">Martes</div>
                            <div class="dias">Miércoles</div>
                            <div class="dias">Jueves</div>
                            <div class="dias">Viernes</div>
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
    <script type="module" src="/frontend/js/confirm-espacios.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
    <script src="/backend/functions/Horarios/select-horarios.js"></script>
    <script src="./js/horarios-lunes.js"></script>

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

</html>