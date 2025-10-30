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
                    <?= t("Reservar espacio") ?>
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

                <div id="div-dialogs">
                    <div class="overlay">
                        <div class="dialogs" id="dialogs">
                            <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                    alt=""></button>
                            <form class="registro-div reserva-form">
                                <h1><?= t("Reservar espacio") ?></h1>
                                <hr>
                                <div class="div-labels" ><i><b>Nota:</b> Solamente puedes reservar espacios en los momentos que tienes clases.</i></div>
                                <div class="div-labels">
                                    <label for="dia" class="label">En el dia:</label>
                                    <input type="date" name="dia_reserva" id="dia_reserva" class="input-register" required>
                                </div>
                                <div class="div-labels">
                                    <label for="espacio" class="label">Espacio a reservar: </label>
                                    <select name="espacio_reservar" id="espacio_reservar" type="text" class="input-register">
                                        <option value=""></option>
                                        <?php while ($row = mysqli_fetch_array($espacios_sin_general)): ?>
                                            <option value="<?= $row['id_espacio'] ?>">
                                                <?= $row['nombre'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="div-labels" id="horas_reserva">
                                    <label for="nose" class="label">Cantidad de horas a reservar:</label>
                                    <input type="number" name="cantidad_horas_reserva" id="cantidad_horas_reserva"
                                        class="input-register" required>
                                </div>
                                <div id="campos-reservas"></div>
                                <div class="div-botones-register">
                                    <input class="btn-enviar-registro" type="submit" value="Registrar"
                                        name="registrarReservaEspacio"></input>
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
<?php include_once("./Complementos/footer.php") ?>
<script type="module" src="/frontend/js/prueba.js"></script>
<script src="./js/horarios-lunes.js"></script>
<script src="js/Register-Modal.js"></script>
<script type='module' src="../backend/functions/Profesores/inasistencia/marcar_inasistencia.js"></script>

<!-- Registros -->
<script type="module" src="../backend/functions/dependencias/crear_campos.js"></script>
<script type="module" src="js/validaciones-registro.js" defer></script>
<script type="module" src="../backend/functions/reserva_espacio/reservar_espacio.js"></script>
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