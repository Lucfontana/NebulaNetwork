<?php
session_start();
?>
<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
<title>Mostrar Información</title>


    <?php include 'nav.php'; ?>

    <body>
        <main>
            <div id="contenido-mostrar-datos">
                <h1 id="titulo-mostrar-informacion">Mostrar Información</h1>
                <div class="filtros"> <label for="horario-select">Seleccione Dato a Mostrar:</label>
                    <select name="informacion" class="salones-select" id="informacion-change" onchange="cambiarDato(this.value)">
                        <option value="0">Seleccione Dato</option>
                        <option value="asignatura">Asignaturas</option>
                        <option value="cursos">Cursos</option>
                        <option value="espacios">Espacios Fisicos</option>
                        <option value="orientaciones">Orientaciones</option>
                        <option value="profesores">Profesores</option>
                        <option value="recursos">Recursos</option>
                        <option value="superusuarios">Super Usuarios</option>
                    </select>
                </div>
                <div data-seccion="asignatura" class="seccion-oculta">
                    <?php include_once("./asignaturas.php") ?>
                </div>

                <div data-seccion="cursos" class="seccion-oculta">
                    <?php include_once("./Cursos.php") ?>
                </div>

                <div data-seccion="espacios" class="seccion-oculta">
                    <?php include_once("./Espacios.php") ?>
                </div>

                <div data-seccion="orientaciones" class="seccion-oculta">
                    <?php include_once("./Orientaciones.php") ?>
                </div>

                <div data-seccion="profesores" class="seccion-oculta">
                    <?php include_once("./Profesores.php") ?>
                </div>

                <div data-seccion="recursos" class="seccion-oculta">
                    <?php include_once("./Recursos.php") ?>
                </div>

                <div data-seccion="superusuarios" class="seccion-oculta">
                    <?php include_once("./SuperUsuarios.php") ?>
                </div>
            </div>
        </main>

        <footer id="footer" class="footer">
            <p> &copy; <b> <?= t("footer") ?> </b></p>

        </footer>

    <?php endif; ?>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script type="module" src="/frontend/js/confirm-asignatura.js"></script>
    <script type="module" src="/frontend/js/confirm-cursos.js"></script>
    <script type="module" src="/frontend/js/confirm-espacios.js"></script>
    <script type="module" src="/frontend/js/confirm-orientacion.js"></script>
    <script type="module" src="/frontend/js/confirm-profesores.js"></script>
    <script type="module" src="/frontend/js/confirm-recursos.js"></script>
    <script type="module" src="/frontend/js/confirm-superusuario.js"></script>

    <script type="module" src="/frontend/js/prueba.js"></script>
    <script src="./js/Mostrar-informacion-general.js"></script>
    </body>