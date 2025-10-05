<?php

include_once('../backend/db/conexion.php');

$con = conectar_a_bd();
$sql = "SELECT * FROM espacios_fisicos";
$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="style/style.css">

<?php if (!isset($_SESSION['nivel_acceso'])):?>
    <?php include_once('error.php')?>
<?php else:?>

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>

    <main>

        <div id="contenido" class="contenido">

            <div id="register-content">
                <div class="article-register">
                    <div>
                        <h1> Registro de Profesores</h1>
                    </div>
                    <button type="button" id="Profesores-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>

                <div class="article-register">
                    <div>
                        <h1> Registro de SuperUsuarios</h1>
                    </div>
                    <button type="button" id="Adscriptas-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>

                <div class="article-register">
                    <div>
                        <h1> Registro de Recursos</h1>
                    </div>
                    <button type="button" id="Recursos-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>

                <div class="article-register">
                    <div>
                        <h1> Registro de Espacios</h1>
                    </div>
                    <button type="button" id="Salones-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>

                <div class="article-register">
                    <div>
                        <h1> Registro de Cursos</h1>
                    </div>
                    <button type="button" id="Salones-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>

                <div class="article-register">
                    <div>
                        <h1> Registro de Asignaturas</h1>
                    </div>
                    <button type="button" id="Salones-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>

                <div class="article-register">
                    <div>
                        <h1> Registro de Horarios</h1>
                    </div>
                    <button type="button" id="Salones-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>
                <div class="article-register">
                    <div>
                        <h1> Registro de Dependencias</h1>
                    </div>
                    <button type="button" id="Salones-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>
                <div class="article-register">
                    <div>
                        <h1> Registro de Orientaciones</h1>
                    </div>
                    <button type="button" id="Salones-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Abrir Registro
                    </button>
                </div>
            </div>
        </div>

        <!--    Inicio de Ventanas Emergentes    -->

        <div id="div-dialogs">
            <dialog>
                <form id="form-registro" class="registro-div profesores-form">
                    <h1>Registro de Profesores</h1>
                    <hr>
                    <div class="div-labels">
                        <label for="CI" class="label">Cedula de Identidad:</label>
                        <input class="input-register" type="text" name="CI" id="ciProfesor" maxlength="8"
                            pattern="\d{8}" required placeholder="Ingresa sin puntos ni guiones">
                    </div>
                    <div class="div-labels">
                        <label for="contrasena" class="label">Contraseña:</label>
                        <input class="input-register" type="password" name="contrasena" id="contrasenaProfesor"
                            maxlength="8" pattern="\d{8}" required placeholder="Ingrese Contraseña">
                    </div>
                    <div class="div-labels">
                        <label for="name" class="label">Nombre:</label>
                        <input class="input-register" type="text" name="name" id="nombreProfesor" maxlength="20"
                            minlength="3" required placeholder="Ingresa nombre">
                    </div>
                    <div class="div-labels">
                        <label for="apellido" class="label">Apellido:</label>
                        <input class="input-register" type="text" name="apellido" id="apellidoProfesor" maxlength="20"
                            minlength="3" required placeholder="Ingresa apellido">
                    </div>
                    <div class="div-labels">
                        <label for="email" class="label">Email:</label>
                        <input class="input-register" type="email" name="email" id="emailProfesor" maxlength="30"
                            minlength="8" required placeholder="Ingresa Email">
                    </div>
                    <div class="div-labels">
                        <label for="nac" class="label">Fecha Nacimiento:</label>
                        <input class="input-register" type="date" name="nac" id="fechaNacimientoProfesor" maxlength="30"
                            minlength="8" required>
                    </div>
                    <div class="div-labels">
                        <label for="direc" class="label">Dirección:</label>
                        <input class="input-register" type="text" name="direc" id="direccionProfesor" maxlength="100"
                            minlength="1 " required placeholder="Ingresa dirección">
                    </div>
                    <div class="div-botones-register">
                        <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                            name="registroProfesor"></input>
                </form>

                <!-- 
                    Se tiene que declarar el boton como de tipo "button" pq por defecto,
                    los botones adentro de un formulario son de tipo submit, por lo tanto
                    esto causaba que el formulario se enviara cuando necesitabamos cerrar 
                    el modal. Esta explicacion sirve para todos los botones de ceerrar que hay-->
                <button class="btn-Cerrar" type="button">Cerrar</button>
        </div>
        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div superusuarios-form">
                <h1>Registro de SuperUsuarios</h1>
                <hr>

                <div class="div-labels">
                    <label for="CI" class="label">Cedula de Identidad:</label>
                    <input class="input-register" type="text" name="CI" id="ciSuperusuario" maxlength="8"
                        pattern="\d{8}" required placeholder="Ingresa sin puntos ni guiones">
                </div>
                <div class="div-labels">
                    <label for="contrasena" class="label">Contraseña:</label>
                    <input class="input-register" type="password" name="password" id="contrasenaSuperusuario"
                        maxlength="8" pattern="\d{8}" required placeholder="Ingrese Contraseña">
                </div>
                <div class="div-labels">
                    <label for="name" class="label">Nombre:</label>
                    <input class="input-register" type="text" name="name" id="nombreSuperusuario" maxlength="20"
                        minlength="3" required placeholder="Ingresa nombre">
                </div>
                <div class="div-labels">
                    <label for="apellido" class="label">Apellido:</label>
                    <input class="input-register" type="text" name="apellido" id="apellidoSuperusuario" maxlength="20"
                        minlength="3" required placeholder="Ingresa apellido">
                </div>
                <div class="div-labels">
                    <label for="email" class="label">Email:</label>
                    <input class="input-register" type="email" name="email" id="emailSuperusuario" maxlength="30"
                        minlength="8" required placeholder="Ingresa Email">
                </div>
                <div class="div-labels">
                    <div class="div-labels">
                        <label for="acceso" class="label">Nivel de Acceso:</label>
                        <select class="input-register" type="text" name="acceso" id="acceso" maxlength="20"
                            minlength="8" required placeholder="">
                            <option value=""></option>
                            <option value="1">1 - Adscripta</option>
                            <option value="2">2 - Secretaria</option>
                            <option value="3">3 - Administrador</option>
                        </select>
                    </div>
                    <div class="div-botones-register">
                        <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                            name="registrarSuperuser"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div recursos-form" action="../backend/functions/recursos_func.php"
                method="POST">
                <h1>Registro de Recursos</h1>
                <hr>
                <div class="div-labels">
                    <label for="name" class="label">Nombre:</label>
                    <input class="input-register" type="text" name="name" id="nombreRecurso" maxlength="40"
                        minlength="3" required placeholder="Ingresa nombre">
                </div>
                <div class="div-labels">
                    <label for="estado" class="label">Estado:</label>
                    <select class="input-register" type="text" name="estado" id="estadoRecurso" maxlength="20"
                        minlength="8" required placeholder="">
                        <option value=""></option>
                        <option value="uso">Uso</option>
                        <option value="libre">Libre</option>
                        <option value="roto">Roto</option>
                    </select>
                </div>

                <!-- que hace esto??

        En resumen, agarra a todos los nombres de espacios fisicos que hay y los pone
        como opciones. Si se selecciona x salon, se pasa su id como value asi se registra
        la conexion en la BD  -->
                <div class="div-labels">
                    <label for="pertenece" class="label">Pertenece a:</label>
                    <select name="pertenece" id="pertenece" type="text" class="input-register">
                        <option value=""></option>

                        <!-- ARREGLAR!! SI SE SELECCIONA GENERAL NO FUNCIONA!! -->
                        <option value="general">General</option>
                        <?php while ($row = mysqli_fetch_array($result)): ?>
                        <option value="<?= $row['id_espacio']?>">
                            <?= $row['nombre']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="tipo" class="label">Tipo:</label>
                    <select class="input-register" type="text" name="tipo" id="tipo" maxlength="20" minlength="8"
                        required placeholder="">
                        <option value=""></option>
                        <option value="interno">Interno</option>
                        <option value="externo">Externo</option>
                    </select>
                    <div class="div-botones-register">
                        <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                            name="registrarRecurso"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div espacios-form" action="../backend/functions/espacios_func.php"
                method="POST">
                <h1>Registro de Espacios</h1>
                <hr>
                <div class="div-labels">
                    <label for="name" class="label">Nombre:</label>
                    <input class="input-register" type="text" name="name" id="nombreEspacio" maxlength="40"
                        minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="div-labels">
                    <label for="capacity" class="label">Capacidad:</label>
                    <input class="input-register" type="number" name="capacity" id="capacidadEspacio" maxlength="3"
                        minlength="1" required placeholder="Ingresa capacidad">
                </div>
                <div class="div-labels">
                    <label for="tipo" class="label">Tipo:</label>
                    <select class="input-register" type="text" name="tipo" id="tipoEspacio" required placeholder="">
                        <option value=""></option>
                        <option value="aula">Aula</option>
                        <option value="salon">Salón</option>
                        <option value="laboratorio">Laboratorio</option>
                        <option value="SUM">SUM</option>
                    </select>
                </div>
                <div class="div-botones-register">
                    <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                        name="registrarEspacio"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div cursos-form" action="../backend/functions/cursos_func.php"
                method="POST">
                <h1>Registro de Cursos</h1>
                <hr>
                <div class="div-labels">
                    <label for="name" class="label">Nombre:</label>
                    <input class="input-register" type="text" name="name" id="nombreCurso" maxlength="20" minlength="3"
                        required placeholder="Ingresa sin puntos ni guiones">
                </div>
                <div class="div-labels">
                    <label for="capacity" class="label">Capacidad:</label>
                    <input class="input-register" type="number" name="capacity" id="capacidadCurso" maxlength="3"
                        minlength="1" required placeholder="Ingresa sin puntos ni guiones">
                </div>

                <div class="div-labels">
                    <label for="curso_dictado" class="label">Orientacion:</label>
                    <select name="orientacion_en" id="salon_ocupado" type="text" class="input-register">
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($orientacion_info)): ?>
                        <option value="<?= $row['id_orientacion']?>">
                            <?= $row['nombre']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="div-botones-register">
                    <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                        name="registrarCursos"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div asignatura-form">
                <h1>Registro de Asignaturas</h1>
                <hr>
                <div class="div-labels">
                    <label for="name" class="label">Nombre:</label>
                    <input class="input-register" type="text" name="nombreAsignatura" id="nombreAsignatura"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>
                <div class="div-botones-register">
                    <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar Asignatura"
                        name="registrarAsignatura"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div horarios-form"
                action="../backend/functions/horarios/horarios_api.php" method="post">
                <h1>Registro de Horarios</h1>
                <hr>
                <div class="div-labels">
                    <label for="hora_inicio" class="label">Hora de Inicio:</label>
                    <input class="input-register" type="time" name="hora_inicio" id="horaInicioHorario" maxlength="20"
                        minlength="8" required placeholder="Ingresa nombre">
                </div>
                <div class="div-labels">
                    <label for="hora_final" class="label">Hora de Salida:</label>
                    <input class="input-register" type="time" name="hora_final" id="horaFinalHorario" maxlength="20"
                        minlength="8" required placeholder="Ingresa nombre">
                </div>
                <div class="div-labels">
                    <label for="tipo">Tipo de horario:</label>
                    <select class="input-register" type="text" name="tipo_horario" id="tipoHorario" required
                        placeholder="">
                        <option value=""></option>
                        <option value="recreo">Recreo</option>
                        <option value="clase">Clase</option>
                    </select>
                </div>

                <div class="div-botones-register">
                    <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                        name="registroHorario"></input>
                    <button class="btn-Cerrar" type="button">Cerrar</button>
                </div>
            </form>

        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div dependencias-form"
                action="../backend/functions/dependencias/dependencias_api.php" method="POST">
                <h1>Registro de Dependencias</h1>
                <hr>

                <div class="div-labels">
                    <label for="profesor_asignado" class="label">Profesor:</label>
                    <select name="profesor_asignado" id="pertenece" type="text" class="input-register">
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($profesores_info)): ?>
                        <option value="<?= $row['ci_profesor']?>">
                            <?= $row['nombre']?>
                            <?= $row['apellido']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="asignatura_dada" class="label">Asignatura a dictar:</label>
                    <select name="asignatura_dada" id="pertenece" type="text" class="input-register">
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($asignaturas_info)): ?>
                        <option value="<?= $row['id_asignatura']?>">
                            <?= $row['nombre']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="hora_inicio" class="label">Hora de inicio:</label>
                    <select name="hora_inicio" id="hora_inicio" type="text" class="input-register">
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($horarios_info)): ?>
                        <option value="<?= $row['id_horario']?>">
                            <?= $row['hora_inicio']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="hora_final" class="label">Hora final:</label>
                    <select name="hora_final" id="hora_final" type="text" class="input-register">
                        <option value=""></option>
                        <?php 
                mysqli_data_seek($horarios_info, 0); //Reinicia el while para que empiece de cero otra vez (el anterior while lo dejo en el final)
                while ($row = mysqli_fetch_array($horarios_info)): ?>
                        <option value="<?= $row['id_horario']?>">
                            <?= $row['hora_final']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="salon_ocupado" class="label">Salon que ocupa:</label>
                    <select name="salon_ocupado" id="salon_ocupado" type="text" class="input-register">
                        <option value=""></option>
                        <?php 
                mysqli_data_seek($result, 0); //Reinicia el while para que empiece de cero otra vez (el anterior while que utilizo los recursos lo dejo en el final)
                while ($row = mysqli_fetch_array($result)): ?>
                        <option value="<?= $row['id_espacio']?>">
                            <?= $row['nombre']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="curso_dictado" class="label">Curso al que se dicta:</label>
                    <select name="curso_dictado" id="salon_ocupado" type="text" class="input-register">
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($cursos_info)): ?>
                        <option value="<?= $row['id_curso']?>">
                            <?= $row['nombre']?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-botones-register">
                    <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                        name="registrarDependencia"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>

        <dialog>
            <form id="form-registro" class="registro-div asignatura-form"
                action="../backend/functions/orientacion/orientacion_api.php" method="POST">
                <h1>Registro de Orientaciones</h1>
                <hr>
                <div class="div-labels">
                    <label for="name" class="label">Nombre:</label>
                    <input class="input-register" type="text" name="nombreOrientacion" id="nombreOrientacion"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>
                <div class="div-botones-register">
                    <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar orientacion"
                        name="registrarOrientacion"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>
        </div>
    </main>

    <!--    Cierre de Ventanas Emergentes    -->

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>
    <?php endif;?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/Register-Modal.js"></script>
    <script type="module" src="js/validaciones-registro.js" defer></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>