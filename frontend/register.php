<?php

include_once 'functions.php';
include_once('../backend/db/conexion.php');

$con = conectar_a_bd();
$sql = "SELECT * FROM espacios_fisicos";
$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Query espacios sin el espacio general
$sql = "SELECT * FROM espacios_fisicos WHERE nombre != 'general'";
$stmt = $con->prepare($sql);
$stmt->execute();
$espacios_sin_general = $stmt->get_result();

////////////////////////////////
//Query de profesores
$query_profesores = "SELECT * FROM profesores";
$stmt = $con->prepare($query_profesores);
$stmt->execute();
$profesores_info = $stmt->get_result();

////////////////////////////////
//Query de asignaturas
$query_asignaturas = "SELECT * FROM asignaturas";
$stmt = $con->prepare($query_asignaturas);
$stmt->execute();
$asignaturas_info = $stmt->get_result();

////////////////////////////////
//Query de horas
$query_horarios = "SELECT * FROM horarios";
$stmt = $con->prepare($query_horarios);
$stmt->execute();
$horarios_info = $stmt->get_result();

/////////////////////////////////
//Query de cursos
$query_cursos = "SELECT * FROM cursos";
$stmt = $con->prepare($query_cursos);
$stmt->execute();
$cursos_info = $stmt->get_result();

///////////////////////////////////
//Query de orientacion
$query_orientacion = "SELECT * FROM orientacion";
$stmt = $con->prepare($query_orientacion);
$stmt->execute();
$orientacion_info = $stmt->get_result();

session_start();



?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t("title") ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="style/style.css">

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

    <body id="body-register">
        <!-- trae las barras de navegacion (sidebar y superior) -->
        <?php include 'nav.php'; ?>

        <main>

            <div id="contenido" class="contenido">

                <div id="register-content">
                    <div class="article-register">
                        <div>
                            <h1><?= t("header_teachers") ?></h1>
                        </div>
                        <button type="button" id="Profesores-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                            <?= t("btn_open_register") ?>
                        </button>
                    </div>

                    <div class="article-register">
                        <div>
                            <h1><?= t( "header_superusers") ?></h1>
                        </div>
                        <button type="button" id="Adscriptas-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                             <?= t("btn_open_register") ?>
                        </button>
                    </div>

                    <div class="article-register">
                        <div>
                            <h1><?= t("header_resources") ?></h1>
                        </div>
                        <button type="button" id="Recursos-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                             <?= t("btn_open_register") ?>
                        </button>
                    </div>

                    <div class="article-register">
                        <div>
                            <h1><?= t("header_spaces") ?></h1>
                        </div>
                        <button type="button" id="Salones-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                             <?= t("btn_open_register") ?>
                        </button>
                    </div>

                    <div class="article-register">
                        <div>
                            <h1><?= t("header_courses") ?></h1>
                        </div>
                        <button type="button" id="Salones-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                             <?= t("btn_open_register") ?>
                        </button>
                    </div>

                    <div class="article-register">
                        <div>
                            <h1><?= t("header_subjects") ?></h1>
                        </div>
                        <button type="button" id="Salones-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                            <?= t("btn_open_register") ?>
                        </button>
                    </div>

                    <div class="article-register">
                        <div>
                            <h1><?= t("header_schedules") ?></h1>
                        </div>
                        <button type="button" id="Salones-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                             <?= t("btn_open_register") ?>
                        </button>
                    </div>
                    <div class="article-register">
                        <div>
                            <h1><?= t("header_dependencies") ?></h1>
                        </div>
                        <button type="button" id="Salones-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                             <?= t("btn_open_register") ?>
                        </button>
                    </div>
                    <div class="article-register">
                        <div>
                            <h1><?= t("header_orientations") ?></h1>
                        </div>
                        <button type="button" id="Salones-boton" class="btn" data-toggle="modal"
                            data-target="#exampleModal">
                             <?= t("btn_open_register") ?>
                        </button>
                    </div>
                </div>
            </div>

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
                                    pattern="\d{8}" required placeholder="Ingresa sin puntos ni guiones">
                            </div>
                            <div class="div-labels">
                                <label for="contrasena" class="label"><?= t("label_password") ?></label>
                                <input class="input-register" type="password" name="contrasena" id="contrasenaProfesor"
                                    maxlength="8" pattern="\d{8}" required placeholder="Ingrese Contraseña">
                            </div>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="name" id="nombreProfesor" maxlength="20"
                                    minlength="3" required placeholder="Ingresa nombre">
                            </div>
                            <div class="div-labels">
                                <label for="apellido" class="label"><?= t("label_lastname") ?></label>
                                <input class="input-register" type="text" name="apellido" id="apellidoProfesor"
                                    maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                            </div>
                            <div class="div-labels">
                                <label for="email" class="label"><?= t("label_email") ?></label>
                                <input class="input-register" type="email" name="email" id="emailProfesor" maxlength="30"
                                    minlength="8" required placeholder="Ingresa Email">
                            </div>
                            <div class="div-labels">
                                <label for="nac" class="label"><?= t("label_birth") ?></label>
                                <input class="input-register" type="date" name="nac" id="fechaNacimientoProfesor"
                                    maxlength="30" minlength="8" required>
                            </div>
                            <div class="div-labels">
                                <label for="direc" class="label"><?= t("label_address") ?></label>
                                <input class="input-register" type="text" name="direc" id="direccionProfesor"
                                    maxlength="100" minlength="1 " required placeholder="Ingresa dirección">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="Registrar"
                                    name="registroProfesor"></input>
                            </div>

                        </form>

                        <!-- 
                    Se tiene que declarar el boton como de tipo "button" pq por defecto,
                    los botones adentro de un formulario son de tipo submit, por lo tanto
                    esto causaba que el formulario se enviara cuando necesitabamos cerrar 
                    el modal. Esta explicacion sirve para todos los botones de ceerrar que hay-->
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
                                    pattern="\d{8}" required placeholder="Ingresa sin puntos ni guiones">
                            </div>
                            <div class="div-labels">
                                <label for="contrasena" class="label"><?= t("label_password") ?></label>
                                <input class="input-register" type="password" name="password" id="contrasenaSuperusuario"
                                    maxlength="8" pattern="\d{8}" required placeholder="Ingrese Contraseña">
                            </div>
                            <div class="div-labels">
                                <label for="name" class="label"><?= t("label_name") ?></label>
                                <input class="input-register" type="text" name="name" id="nombreSuperusuario" maxlength="20"
                                    minlength="3" required placeholder="Ingresa nombre">
                            </div>
                            <div class="div-labels">
                                <label for="apellido" class="label"><?= t("label_lastname") ?></label>
                                <input class="input-register" type="text" name="apellido" id="apellidoSuperusuario"
                                    maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                            </div>
                            <div class="div-labels">
                                <label for="email" class="label"><?= t("label_email") ?></label>
                                <input class="input-register" type="email" name="email" id="emailSuperusuario"
                                    maxlength="30" minlength="8" required placeholder="Ingresa Email">
                            </div>
                            <div class="div-labels">
                                <div class="div-labels">
                                    <label for="acceso" class="label"><?= t("label_access_level") ?></label>
                                    <select class="input-register" type="text" name="acceso" id="acceso" maxlength="20"
                                        minlength="8" required placeholder="">
                                        <option value=""></option>
                                        <option value="1"><?= t("option_access_1") ?></option>
                                        <option value="2"><?= t("option_access_2") ?></option>
                                        <option value="3"><?= t("option_access_3") ?></option>
                                    </select>
                                </div>
                                <div class="div-botones-register">
                                    <input class="btn-enviar-registro" type="submit" value="Registrar"
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
                                    minlength="3" required placeholder="Ingresa nombre">
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
                                    <option value="general"><?= t("option_general") ?></option>

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
                                    <input class="btn-enviar-registro" type="submit" value="Registrar"
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
                                <label for="name" class="label">Nombre:</label>
                                <input class="input-register" type="text" name="name" id="nombreEspacio" maxlength="40"
                                    minlength="3" required placeholder="Ingresa nombre">
                            </div>

                            <div class="div-labels">
                                <label for="capacity" class="label">Capacidad:</label>
                                <input class="input-register" type="number" name="capacity" id="capacidadEspacio"
                                    maxlength="3" minlength="1" required placeholder="Ingresa capacidad">
                            </div>
                            <div class="div-labels">
                                <label for="tipo" class="label">Tipo:</label>
                                <select class="input-register" type="text" name="tipo" id="tipoEspacio" required
                                    placeholder="">
                                    <option value=""></option>
                                    <option value="aula">Aula</option>
                                    <option value="salon">Salón</option>
                                    <option value="laboratorio">Laboratorio</option>
                                    <option value="SUM">SUM</option>
                                </select>
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="Registrar"
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
                            <h1>Registro de Cursos</h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label">Nombre:</label>
                                <input class="input-register" type="text" name="name" id="nombreCurso" maxlength="20"
                                    minlength="3" required placeholder="Ingresa sin puntos ni guiones">
                            </div>
                            <div class="div-labels">
                                <label for="capacity" class="label">Capacidad:</label>
                                <input class="input-register" type="number" name="capacity" id="capacidadCurso"
                                    maxlength="3" minlength="1" required placeholder="Ingresa sin puntos ni guiones">
                            </div>

                            <div class="div-labels">
                                <label for="curso_dictado" class="label">Orientacion:</label>
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
                                <input class="btn-enviar-registro" type="submit" value="Registrar"
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
                            <h1>Registro de Asignaturas</h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label">Nombre:</label>
                                <input class="input-register" type="text" name="nombreAsignatura" id="nombreAsignatura"
                                    maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="Registrar Asignatura"
                                    name="registrarAsignatura"></input>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div horarios-form">
                            <h1>Registro de Horarios</h1>
                            <hr>
                            <div class="div-labels">
                                <label for="hora_inicio" class="label">Hora de Inicio:</label>
                                <input class="input-register" type="time" name="hora_inicio" id="horaInicioHorario"
                                    maxlength="20" minlength="8" required placeholder="Ingresa nombre">
                            </div>
                            <div class="div-labels">
                                <label for="hora_final" class="label">Hora de Salida:</label>
                                <input class="input-register" type="time" name="hora_final" id="horaFinalHorario"
                                    maxlength="20" minlength="8" required placeholder="Ingresa nombre">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="Registrar"
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
                            <h1>Registro de Dependencias</h1>
                            <hr>

                            <div class="div-labels">
                                <label for="profesor_asignado" class="label">Profesor:</label>
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
                                <label for="asignatura_dada" class="label">Asignatura a dictar:</label>
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
                                <label for="dia" class="label">En el dia:</label>
                                <select class="input-register" type="text" name="dia_dictado" id="dia_dictado" required
                                    placeholder="Ingrese el dia">
                                    <option value=""></option>
                                    <option value="lunes">Lunes</option>
                                    <option value="martes">Martes</option>
                                    <option value="miercoles">Mierccoles</option>
                                    <option value="jueves">Jueves</option>
                                    <option value="viernes">Viernes</option>
                                </select>
                            </div>        

                            <div class="div-labels">
                                <label for="capacity" class="label">Horas que dicta:</label>
                                <input class="input-register" type="number" id="crear_campos"
                                    maxlength="3" minlength="1" required>
                            </div>

                            <div id="campos-dinamicos"></div>

                            <div class="div-labels">
                                <label for="salon_ocupado" class="label">Salon que ocupa:</label>
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
                                <label for="curso_dictado" class="label">Curso al que se dicta:</label>
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
                                <input class="btn-enviar-registro" type="submit" value="Registrar"
                                    name="registrarDependencia"></input>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                                alt=""></button>
                        <form class="registro-div orientacion-form">
                            <h1>Registro de Orientaciones</h1>
                            <hr>
                            <div class="div-labels">
                                <label for="name" class="label">Nombre:</label>
                                <input class="input-register" type="text" name="nombreOrientacion" id="nombreOrientacion"
                                    maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                            </div>
                            <div class="div-botones-register">
                                <input class="btn-enviar-registro" type="submit" value="Registrar orientacion"
                                    name="registrarOrientacion"></input>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!--    Cierre de Ventanas Emergentes    -->

        <footer id="footer" class="footer">
            <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
        </footer>
    <?php endif; ?>

    <!-- Bootstrap -->
    <script type="module" src="../backend/functions/dependencias/crear_campos.js"></script>
    <script type="module" src="js/validaciones-registro.js" defer></script>
    <script type="module" src="js/swalerts.js"></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/frontend/js/Register-Modal.js"></script>
</body>

</html>