<?php
session_start();
?>

<?php if (isset($_SESSION['nivel_acceso'])): ?>
    <title>Mostrar Información</title>
    <?php include_once './Complementos/nav.php'; ?>
    <?php include_once 'register.php'; ?>

    <body id="body-register">
        <main>
            <div id="contenido-mostrar-datos">
                <div class="div-mostrar-datos-main">
                    <h1 id="titulo-mostrar-informacion">Mostrar Información</h1>
                    <div class="filtros"> <label for="horario-select">Seleccione Dato a Mostrar:</label>
                        <select name="informacion" class="salones-select" id="informacion-change" onchange="cambiarDato(this.value)">
                            <option value="0">Seleccione Dato</option>
                            <option value="profesores">Profesores</option>
                            <option value="superusuarios">Super Usuarios</option>
                            <option value="recursos">Recursos</option>
                            <option value="espacios">Espacios Fisicos</option>
                            <option value="cursos">Cursos</option>
                            <option value="asignatura">Asignaturas</option>
                            <option value="orientaciones">Orientaciones</option>
                            <option value="inasistencias">Inasistencias</option>
                            <option value="reserva">Reservas</option>
                        </select>
                    </div>
                </div>

                <div data-seccion="profesores" class="seccion-oculta">
                    <?php include_once("./CRUD/Profesores.php") ?>
                </div>

                <div data-seccion="superusuarios" class="seccion-oculta">
                    <?php include_once("./CRUD/SuperUsuarios.php") ?>
                </div>

                <div data-seccion="recursos" class="seccion-oculta">
                    <?php include_once("./CRUD/Recursos.php") ?>
                </div>

                <div data-seccion="espacios" class="seccion-oculta">
                    <?php include_once("./CRUD/Espacios.php") ?>
                </div>

                <div data-seccion="cursos" class="seccion-oculta">
                    <?php include_once("./CRUD/Cursos.php") ?>
                </div>

                <div data-seccion="asignatura" class="seccion-oculta">
                    <?php include_once("./CRUD/asignaturas.php") ?>
                </div>

                <div data-seccion="orientaciones" class="seccion-oculta">
                    <?php include_once("./CRUD/Orientaciones.php") ?>
                </div>

                <div data-seccion="inasistencias" class="seccion-oculta">
                    <?php include_once("./CRUD/inasistencias.php") ?>
                </div>

                <div data-seccion="reserva" class="seccion-oculta">
                    <?php include_once("./CRUD/reserva.php") ?>
                </div>
            </div>
        </main>

        <footer id="footer" class="footer">
            <p> &copy; <b> <?= t("footer") ?> </b></p>

        </footer>

    <?php elseif (isset($_SESSION['ci'])): ?>
        <title>Mostrar Información</title>

        <?php include_once 'nav.php'; ?>

        <body id="body-register">
            <main>
                <div id="contenido-mostrar-datos">
                    <div class="div-mostrar-datos-main">
                        <h1 id="titulo-mostrar-informacion">Mostrar Información</h1>
                        <div class="filtros"> <label for="horario-select">Seleccione Dato a Mostrar:</label>
                            <select name="informacion" class="salones-select" id="informacion-change" onchange="cambiarDato(this.value)">
                                <option value="0">Seleccione Dato</option>
                                <option value="inasistencias">Inasistencias</option>
                                <option value="reserva">Reservas</option>
                            </select>
                        </div>
                    </div>
                    <div data-seccion="inasistencias" class="seccion-oculta">
                        <?php include_once("./CRUD/inasistencias.php") ?>
                    </div>
                    <div data-seccion="reserva" class="seccion-oculta">
                        <?php include_once("./CRUD/reserva.php") ?>
                    </div>
                </div>
            </main>

            <footer id="footer" class="footer">
                <p> &copy; <b> <?= t("footer") ?> </b></p>
            </footer>

        <?php elseif (!isset($_SESSION['ci'])): ?>
            <?php include_once('error.php') ?>
        <?php endif; ?>

        <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
        <!-- scripts para mostrar informacion -->
        <script type="module" src="/frontend/js/confirm-asignatura.js"></script>
        <script type="module" src="/frontend/js/confirm-cursos.js"></script>
        <script type="module" src="/frontend/js/confirm-espacios.js"></script>
        <script type="module" src="/frontend/js/confirm-orientacion.js"></script>
        <script type="module" src="/frontend/js/confirm-profesores.js"></script>
        <script type="module" src="/frontend/js/confirm-recursos.js"></script>
        <script type="module" src="/frontend/js/confirm-superusuario.js"></script>
        <script type="module" src="/frontend/js/confirm-inasistencia.js"></script>
        <script type="module" src="/frontend/js/confirm-reserva.js"></script>

        <!-- Scripts generales -->
        <script type="module" src="/frontend/js/prueba.js"></script>
        <script src="./js/Mostrar-informacion-general.js"></script>

        <!-- Scripts register -->
        <script type="module" src="../backend/functions/dependencias/crear_campos.js"></script>
        <script type="module" src="js/validaciones-registro.js" defer></script>
        <script type="module" src="js/swalerts.js"></script>
        <script src="./js/togglepasswd.js"></script>


        <!-- Sweet alerts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="/frontend/js/Register-Modal.js"></script>

        </body>