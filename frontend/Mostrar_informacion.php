<?php
session_start();

include_once("functions.php");
?>

<?php if (isset($_SESSION['nivel_acceso'])): ?>
    <title><?= t("title_show_info") ?></title>
    <?php include_once './Complementos/nav.php'; ?>
    <?php include_once 'register.php'; ?>

    <body id="body-register">
        <main>
            <div id="contenido-mostrar-datos">
                <div class="div-mostrar-datos-main">
                    <h1 id="titulo-mostrar-informacion"><?= t("title_show_info") ?></h1>
                    <div class="filtros"> <label for="horario-select"><?= t("label_select_data") ?></label>
                        <select name="informacion" class="salones-select" id="informacion-change" onchange="cambiarDato(this.value)">
                            <option value="0"><?= t("option_select_data")?> </option> 
                            <option value="profesores"><?=t("option_teachers")?></option>
                            <option value="superusuarios"><?=t("option_superusers")?></option>
                            <option value="recursos"><?=t("option_resources")?></option>
                            <option value="espacios"><?=t("option_spaces")?></option>
                            <option value="cursos"><?=t("option_courses")?></option>
                            <option value="asignatura"><?=t("option_subjects")?></option>
                            <option value="orientaciones"><?=t("option_orientations")?></option>
                            <option value="inasistencias"><?=t("option_absences")?></option>
                            <option value="reserva"><?=t("option_reservations")?></option>
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
    <?php elseif (isset($_SESSION['ci'])): ?>
        <title><?= t("title_show_info") ?></title>

        <?php include_once 'Complementos/nav.php'; ?>

        <body id="body-register">
            <main>
                <div id="contenido-mostrar-datos">
                    <div class="div-mostrar-datos-main">
                        <h1 id="titulo-mostrar-informacion"><?= t("title_show_info") ?></h1>
                        <div class="filtros"> <label for="horario-select"><?= t("title_show_info") ?></label>
                            <select name="informacion" class="salones-select" id="informacion-change" onchange="cambiarDato(this.value)">
                                <option value="0"><?= t("label_select_data") ?></option>
                                <option value="inasistencias"><?= t("option_absences") ?></option>
                                <option value="reserva"><?= t("option_reservations") ?></option>
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
        <?php elseif (!isset($_SESSION['ci'])): ?>
            <?php include_once('error.php') ?>
        <?php endif; ?>
        <?php include_once("./Complementos/footer.php") ?>
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