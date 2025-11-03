<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
<?php

include_once 'functions.php';
include_once('../backend/db/conexion.php');
include_once('../backend/queries.php');

$result = query_espaciosfisicos($con);

$espacios_sin_general = query_espacios_sin_general($con);

$profesores_info = query_profesores($con);

$asignaturas_info = query_asignaturas($con);

$horarios_info = query_horarios($con);

$cursos_info = query_cursos($con);

$orientacion_info = query_orientacion($con);


?>
            <!--    Inicio de Ventanas Emergentes    -->

            <div id="div-dialogs">

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div profesores-form">
                            <h1><?= t("header_teachers") ?></h1>
                            <hr>
                            <div class="div-labels">
                                <label for="CI" class="label"><?= t("label_ci") ?></label>
                                <input class="input-register" type="text" name="CI" id="ciProfesor" maxlength="8"
                                    pattern="\d{8}" required placeholder="<?= t("placeholder_ci") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="contrasena" class="label"><?= t("label_password") ?></label>
                                <div class="contenedor-password-register">
                                    <input class="input-register" type="password" name="contrasena" id="contrasenaProfesor"
                                        maxlength="8" pattern="\d{8}" required placeholder="<?= t("placeholder_password") ?>">
                                    <i class="far fa-eye fa-eye-slash togglePassword"></i>
                                </div>
                            </div>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="name" id="nombreProfesor" maxlength="20"
                                    minlength="3" required placeholder="<?= t("placeholder_name") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="apellido" class="label"><?= t("label_lastname") ?></label>
                                <input class="input-register" type="text" name="apellido" id="apellidoProfesor"
                                    maxlength="20" minlength="3" required placeholder="<?= t("placeholder_lastname") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="email" class="label"><?= t("label_email") ?></label>
                                <input class="input-register" type="email" name="email" id="emailProfesor" maxlength="30"
                                    minlength="8" required placeholder="<?= t("placeholder_email") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="nac" class="label"><?= t("label_birth") ?></label>
                                <input class="input-register" type="date" name="nac" id="fechaNacimientoProfesor"
                                    maxlength="30" minlength="8" required>
                            </div>
                            <div class="div-labels">
                                <label for="direc" class="label"><?= t("label_address") ?></label>
                                <input class="input-register" type="text" name="direc" id="direccionProfesor"
                                    maxlength="100" minlength="1 " required placeholder="<?= t("placeholder_address") ?>">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                    name="registroProfesor"></input>
                            </div>

                        </form>

                        <!-- 
                    Se tiene que declarar el boton como de tipo "button" pq por defecto,
                    los botones adentro de un formulario son de tipo submit, por lo tanto
                    esto causaba que el formulario se enviara cuando necesitabamos cerrar 
                    el modal. Esta explicacion sirve para todos los botones de cerrar que hay-->
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div superusuarios-form">
                            <h1><?= t("header_superusers") ?></h1>
                            <hr>

                            <div class="div-labels">
                                <label for="CI" class="label"><?= t("label_ci") ?></label>
                                <input class="input-register" type="text" name="CI" id="ciSuperusuario" maxlength="8"
                                    pattern="\d{8}" required placeholder="<?= t("placeholder_ci") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="contrasena" class="label"><?= t("label_password") ?></label>
                                <div class="contenedor-password-register">
                                    <input class="input-register" type="password" name="password" id="contrasenaSuperusuario"
                                        maxlength="8" pattern="\d{8}" required placeholder="<?= t("placeholder_password") ?>">
                                    <i class="far fa-eye fa-eye-slash togglePassword"></i>
                                </div>
                            </div>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="name" id="nombreSuperusuario" maxlength="20"
                                    minlength="3" required placeholder="<?= t("placeholder_name") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="apellido" class="label"><?= t("label_lastname") ?></label>
                                <input class="input-register" type="text" name="apellido" id="apellidoSuperusuario"
                                    maxlength="20" minlength="3" required placeholder="<?= t("placeholder_lastname") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="email" class="label"><?= t("label_email") ?></label>
                                <input class="input-register" type="email" name="email" id="emailSuperusuario"
                                    maxlength="30" minlength="8" required placeholder="<?= t("placeholder_email") ?>">
                            </div>
                            <div class="div-labels">
                                
                                <label for="acceso" class="label"><?= t("label_access_level") ?></label>
                                <select class="input-register" type="text" name="acceso" id="acceso" maxlength="20"
                                    minlength="8" required placeholder="">
                                    <option value=""></option>
                                    <option value="1"><?= t("option_access_1") ?></option>
                                    <option value="2"><?= t("option_access_2") ?></option>
                                    <option value="3"><?= t("option_access_3") ?></option>
                                </select>
                                
                                <div class="div-botones-register">
                                    <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                        name="registrarSuperuser"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div recursos-form">
                            <h1><?= t("header_resources") ?></h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="name" id="nombreRecurso" maxlength="40"
                                    minlength="3" required placeholder="<?= t("placeholder_name") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="estado" class="label"><?= t("label_state") ?></label>
                                <select class="input-register" type="text" name="estado" id="estadoRecurso" maxlength="20"
                                    minlength="8" required placeholder="">
                                    <option value=""></option>
                                    <option value="uso"><?= t("option_use") ?></option>
                                    <option value="libre"><?= t("option_free") ?></option>
                                    <option value="roto"><?= t("option_broken") ?></option>
                                </select>
                            </div>

                            <!-- que hace esto??

        En resumen, agarra a todos los nombres de espacios fisicos que hay y los pone
        como opciones. Si se selecciona x salon, se pasa su id como value asi se registra
        la conexion en la BD  -->
                            <div class="div-labels">
                                <label for="pertenece" class="label"><?= t("label_belongs_to") ?></label>
                                <select name="pertenece" id="pertenece_a_espacio" type="text" class="input-register">
                                    <option value=""></option>

                                    <!-- ARREGLAR!! SI SE SELECCIONA GENERAL NO FUNCIONA!! -->

                                    <!-- ARREGLAR!! SI SE SELECCIONA GENERAL NO FUNCIONA!! -->

                                    <?php while ($row = mysqli_fetch_array($result)): ?>
                                        <option value="<?= $row['id_espacio'] ?>">
                                            <?= $row['nombre'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-labels">
                                <label for="tipo" class="label"><?= t("label_type") ?></label>
                                <select class="input-register" type="text" name="tipo" id="tipo" maxlength="20"
                                    minlength="8" required placeholder="">
                                    <option value=""></option>
                                    <option value="interno"><?= t("option_internal") ?></option>
                                    <option value="externo"><?= t("option_external") ?></option>
                                </select>
                                <div class="div-botones-register">
                                    <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                        name="registrarRecurso"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div espacios-form">
                            <h1><?= t("header_spaces") ?></h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="name" id="nombreEspacio" maxlength="40"
                                    minlength="3" required placeholder="<?= t("placeholder_name") ?>">
                            </div>

                            <div class="div-labels">
                                <label for="capacity" class="label"><?= t("label_capacity") ?></label>
                                <input class="input-register" type="number" name="capacity" id="capacidadEspacio"
                                    maxlength="3" minlength="1" required placeholder="<?= t("placeholder_capacity") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="tipo" class="label"><?= t("label_type") ?></label>
                                <select class="input-register" type="text" name="tipo" id="tipoEspacio" required
                                    placeholder="">
                                    <option value=""></option>
                                    <option value="aula"><?= t("option_aula") ?></option>
                                    <option value="salon"><?= t("option_salon") ?></option>
                                    <option value="laboratorio"><?= t("option_lab") ?></option>
                                    <option value="SUM"><?= t("option_sum") ?></option>
                                </select>
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                    name="registrarEspacio"></input>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div cursos-form" action="../backend/functions/cursos_func.php" method="POST">
                            <h1><?= t("header_courses") ?></h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="name" id="nombreCurso" maxlength="20"
                                    minlength="3" required placeholder="<?= t("placeholder_name") ?>">
                            </div>
                            <div class="div-labels">
                                <label for="capacity" class="label"><?= t("label_capacity") ?></label>
                                <input class="input-register" type="number" name="capacity" id="capacidadCurso"
                                    maxlength="3" minlength="1" required placeholder="<?= t("placeholder_capacity") ?>">
                            </div>

                            <div class="div-labels">
                                <label for="curso_dictado" class="label"><?= t("label_orientation") ?></label>
                                <select name="orientacion_en" id="salon_ocupado" type="text" class="input-register">
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($orientacion_info)): ?>
                                        <option value="<?= $row['id_orientacion'] ?>">
                                            <?= $row['nombre'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register") ?>"
                                    name="registrarCursos"></input>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div asignatura-form">
                            <h1><?= t("header_subjects") ?></h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="nombreAsignatura" id="nombreAsignatura"
                                    maxlength="20" minlength="3" required placeholder="<?= t("placeholder_name") ?>">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register_subject") ?>"
                                    name="registrarAsignatura"></input>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div orientacion-form">
                            <h1><?= t("header_orientations") ?></h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="nombreOrientacion" id="nombreOrientacion"
                                    maxlength="20" minlength="3" required placeholder="<?= t("placeholder_name") ?>">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="<?= t("btn_register_orientation") ?>"
                                    name="registrarOrientacion"></input>
                            </div>

                        </form>
                    </div>
                </div>

                

            </div>

<?php endif; ?>