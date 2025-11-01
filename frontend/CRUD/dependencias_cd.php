<div class="overlay">
    <div class="dialogs">
        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                alt=""></button>
        <form class="registro-div dependencias-form" action="../backend/functions/dependencias/dependencias_api.php"
            method="POST">
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
                <input class="input-register" type="number" id="crear_campos" maxlength="3" minlength="1" required>
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

<div class="overlay">
    <div class="dialogs">
        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                alt=""></button>
        <form class="registro-div eliminar-clase-form">
            <h1><?= t("Eliminar Clase") ?></h1>
            <hr>
            <div class="div-labels">
                <label for="dia_eliminar" class="label"><?= t("En el dia: ") ?></label>
                <select name="dia_eliminar" id="dia_eliminar" class="input-register">
                    <option value=""></option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miercoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                </select>
            </div>
            <div class="div-labels">
                <label for="curso_eliminar" class="label"><?= t("Curso al que se dicta: ") ?></label>
                <select name="curso_eliminar" id="curso_eliminar" type="text" class="input-register" required>
                    <option value=""></option>
                    <?php
                    mysqli_data_seek($cursos_info, 0); //Reinicia el while para que empiece de cero otra vez (el anterior while que utilizo los recursos lo dejo en el final)
                    while ($row = mysqli_fetch_array($cursos_info)): ?>
                        <option value="<?= $row['id_curso'] ?>">
                            <?= $row['nombre'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="div-labels">
                <label for="hora_eliminar" class="label"><?= t("Hora a eliminar: ") ?></label>
                <select name="hora_eliminar" id="hora_eliminar" type="text" class="input-register" required>
                    <option value=""></option>
                    <?php
                    mysqli_data_seek($horarios_info, 0); //Reinicia el while para que empiece de cero otra vez (el anterior while que utilizo los recursos lo dejo en el final)
                    while ($row = mysqli_fetch_array($horarios_info)): ?>
                        <option value="<?= $row['id_horario'] ?>">
                            <?= $row['hora_inicio'] . '-' . $row['hora_final'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="div-botones-register">
                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                    name="registroHorario"></input>
            </div>
        </form>
    </div>
</div>

<!-- <script src=""></script> -->