<?php

//IMPORTANTISIMO!!!! TODA LA LOGICA DEL CALENDARIO ESTA ACA!!!
include_once("../backend/functions/Horarios/logica_calendario.php");

?>

<title><?= t("title_schedules") ?></title>
<?php include './Complementos/nav.php'; ?>

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
                            <option value="0"><?= t("placeholder_course") ?></option>
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
                            <div class="horario-dia" id="horario-<?= $dia_celu ?>"
                                style="<?= $dia_celu === 'lunes' ? '' : 'display:none;' ?>">
                                <?php if (isset($_GET['curso_id'])): ?>
                                    <?php echo cargar_horarios($query4, [$dia_celu], $materias_por_dia, "nombre_asignatura", "nombre_profesor");
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
                            <?= t("btn_add_hours") ?>                        </button>
                        <button class="btn btn-register-horario" data-toggle="modal">
                            <?= t("btn_add_schedules") ?>
                        </button>
                        <button class="btn btn-register-horario" data-toggle="modal">
                            <?= t("btn_delete_schedules") ?>
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
                            <?php mysqli_data_seek($espacios_sin_general, 0); ?>
                            <?php while ($row3 = mysqli_fetch_array($query3)): ?>
                                <option value="<?= $row3['id_espacio'] ?>" <?= (isset($_GET['espacio_id']) && $_GET['espacio_id'] == $row3['id_espacio']) ? 'selected' : '' ?>><?= $row3['nombre'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div id="div-curso" style="display: block;">
                        <label for="Cursos" id="select-cursos"><?= t("label_select_course") ?></label>
                        <select name="Cursos" class="cursos-select" id="cursos-select" onchange="cambiarCurso(this.value)">
                            <option value="0"><?= t("placeholder_course") ?></option>
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
                            <div class="horario-dia" id="horario-<?= $dia_celu ?>"
                                style="<?= $dia_celu === 'lunes' ? '' : 'display:none;' ?>">
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

                <!-- Modal para crear el cronograma de horarios -->
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
                                <input class="input-register" type="time" name="hora_final" id="horaFinalHorario"
                                    maxlength="20" minlength="8" required placeholder="<?= t("placeholder_end_time") ?>">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                    name="registroHorario"></input>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Trae los formularios para agregar y eliminar horarios (clases) -->
                <?php include_once("CRUD/dependencias_cd.php")?>

        </main>
    </body>

    <!-- VISTA DEL PROFESOR -->
<?php else: ?>

    <body id="body-register">
        <main>
            <div id="contenido-mostrar-datos">
                <h1><?= t("title_my_schedules") ?></h1>

                <button type="button" id="Profesores-boton" class="btn-primary btn" data-toggle="modal"
                    data-target="#exampleModal">
                    <?= t("btn_register_absence") ?>
                </button>

                <button type="button" id="espacios_reserva_boton" class="btn-primary btn" data-toggle="modal"
                    data-target="#exampleModal">
                    <?= t("btn_reserve_space") ?>
                </button>

                <?php include_once("CRUD/inasistencia_add.php") ?> <!-- Div para registrar la inasistencia -->

                <?php include_once("CRUD/reservas_esp_add.php") ?> <!-- Div para registrar reservas -->

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
                            <div class="horario-dia" id="horario-<?= $dia_celu ?>"
                                style="<?= $dia_celu === 'lunes' ? '' : 'display:none;' ?>">
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
<?php include_once("./Complementos/footer.php") ?>

<script type="module" src="/frontend/js/prueba.js"></script>
<script src="./js/horarios-calendario.js"></script>
<script src="js/Register-Modal.js"></script>
<script type='module' src="../backend/functions/Profesores/inasistencia/marcar_inasistencia.js"></script>

<!-- Registros -->
<script type="module" src="../backend/functions/dependencias/crear_campos.js"></script>
<script type="module" src="js/validaciones-registro.js" defer></script>
<script type="module" src="../backend/functions/reserva_espacio/reservar_espacio.js"></script>
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