<?php

include_once('../backend/db/conexion.php');
include_once('../backend/queries.php');
include_once('../backend/helpers.php');
$con = conectar_a_bd();

$profesores_info = query_profesores($con);

$recursos_info = query_recursos($con);

$prestamos_info = query_prestamos($con);

session_start();

if (!isset($_SESSION['acceso']) && isset($_SESSION['ci'])) {
    $ci_profesor = (int)$_SESSION['ci'];
}

$prestamos_info2 = query_prestamos_profesores($con, $ci_profesor);

?>

<title>Prestar Recursos</title>
<link rel="stylesheet" href="style/style.css">

<?php if (isset($_SESSION['nivel_acceso'])): ?>
    <?php include './Complementos/nav.php'; ?>

    <body id="body-register">
        <!-- trae las barras de navegacion (sidebar y superior) -->
        <main>
            <div id="register-content">
                <div class="article-register">
                    <div>
                        <h1><?= t("table_header_person") ?></h1>
                    </div>
                    <button type="button" id="Adscriptas-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Prestar
                    </button>
                </div>

            </div>
            </div>


            <!--    Inicio de Ventanas Emergentes    -->

            <div id="div-dialogs">

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png" alt=""></button>
                        <form id="form-registro" class="registro-div prestar-form">
                            <h1><?= t("table_header_person") ?></h1>
                            <hr>

                            <div class="div-labels">
                                <label for="profesor_asignado" class="label"><?= t("label_teacherlend") ?></label>
                                <select name="profesor_asignado" id="profesor_asignado" type="text" class="input-register" required>
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($profesores_info)): ?>
                                        <option value="<?= $row['ci_profesor'] ?>"><?= $row['nombre'] ?> <?= $row['apellido'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-labels">
                                <label for="recurso_prestado" class="label"><?= t("label_resource") ?></label>
                                <select name="recurso_prestado" id="recurso_prestado" type="text" class="input-register" required>
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($recursos_info)): ?>
                                        <option value="<?= $row['id_recurso'] ?>"><?= $row['nombre'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-botones-register">
                                <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                                    name="prestarRecurso"></input>
                        </form>
                    </div>
                </div>
        </main>
        <main>

            <div id="contenido-mostrar-datos">
                <div class="div-mostrar-datos">
                    <h1><?= t("title_lend_resources") ?></h1>
                </div>
                <div class="datos-grid prestamos-grid">
                    <!-- Cabecera -->
                    <div class="grid-header prestamos-header">
                        <div class="grid-cell id">ID</div>
                        <div class="grid-cell nombre-titulo"><?= t("table_header_person") ?></div>
                        <div class="grid-cell nombre-titulo"><?= t("table_header_resource") ?></div>
                        <div class="grid-cell nombre-titulo"><?= t("table_header_admin") ?></div>
                        <div class="grid-cell nombre-titulo"><?= t("table_header_start_time") ?></div>
                        <div class="grid-cell titulo-ult"><?= t("table_header_end_time") ?></div>
                        <div class="grid-cell boton-titulo"><?= t("table_header_status") ?></div>
                    </div>

                    <!-- Filas de datos -->
                    <?php
                    if (mysqli_num_rows($prestamos_info) > 0) {
                        while ($row = mysqli_fetch_array($prestamos_info)):
                    ?>
                            <div class="grid-row prestamos-row mostrar-datos">
                                <div class="grid-cell"><?= $row['id_solicita'] ?></div>
                                <div class="grid-cell">
                                    <?= $row['nombre_profesor'] . ' ' . $row['apellido_profesor'] ?>
                                    <br><small>(CI: <?= $row['ci_profesor'] ?>)</small>
                                </div>
                                <div class="grid-cell"><?= $row['nombre_recurso'] ?></div>
                                <div class="grid-cell"><?= $row['nombre_su'] . ' ' . $row['apellido_su'] ?></div>
                                <div class="grid-cell"><?= date('d/m/Y H:i:s', strtotime($row['hora_presta'])) ?></div>
                                <div class="grid-cell"><?= $row['hora_vuelta'] ? date('d/m/Y H:i', strtotime($row['hora_vuelta'])) : 'Pendiente' ?></div>
                                <div class="grid-cell">
                                    <?php if ($row['hora_vuelta']): ?>
                                        <span style="color: green; margin-left: 20px;"><?= t("status_returned") ?></span>
                                    <?php else: ?>
                                        <a class="boton-datos-editar botones-datos btn-devolver"
                                            data-id="<?= $row['id_solicita'] ?>"
                                            data-recurso="<?= $row['id_recurso'] ?>"
                                            data-nombre-recurso="<?= htmlspecialchars($row['nombre_recurso']) ?>">
                                            Devolver
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    } else {
                        echo '<div class="grid-row" style="text-align:center;"><div class="grid-cell" style="grid-column: 1 / -1;">No hay préstamos registrados</div></div>';
                    }
                    ?>
                </div>


                <!-- Vista de celular -->
                <?php mysqli_data_seek($prestamos_info, 0); ?>
                <div class="flex-mostrar-datos">
                    <?php
                    if (mysqli_num_rows($prestamos_info) > 0) {
                        while ($row = mysqli_fetch_array($prestamos_info)):
                    ?>
                            <div class="datos-header-celu">
                                <div class="datos-tabla-flex">
                                    <div class="nombre-titulo grid-cell flex-header">
                                        <?= $row['nombre_profesor'] . ' ' . $row['apellido_profesor'] . ' - ' ?>
                                        <br><small>( CI: <?= $row['ci_profesor'] ?> )</small>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill mostrar-informacion-oculta" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill icono-guardar-informacion" viewBox="0 0 16 16">
                                            <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="informacion-escondida">
                                <div class="datos-tabla-flex">
                                    <div class="grid-cell">ID SOLICITA: <?= $row['id_solicita'] ?></div>
                                </div>
                                <div class="datos-tabla-flex">
                                    <div class="grid-cell"><?= $row['nombre_recurso'] ?></div>
                                </div>
                                <div class="datos-tabla-flex">
                                    <div class="grid-cell"><?= $row['nombre_su'] . ' ' . $row['apellido_su'] ?></div>
                                </div>
                                <div class="datos-tabla-flex">
                                    <div class="grid-cell"><?= date('d/m/Y H:i:s', strtotime($row['hora_presta'])) ?></div>
                                </div>
                                <div class="datos-tabla-flex">
                                    <div class="grid-cell"><?= $row['nombre_recurso'] ?></div>
                                </div>
                                <div class="grid-cell">
                                    <?php if ($row['hora_vuelta']): ?>
                                        <span style="color: green; margin-left: 20px;"><?= t("status_returned") ?></span>
                                    <?php else: ?>
                                        <a class="boton-datos-editar botones-datos btn-devolver"
                                            data-id="<?= $row['id_solicita'] ?>"
                                            data-recurso="<?= $row['id_recurso'] ?>"
                                            data-nombre-recurso="<?= htmlspecialchars($row['nombre_recurso']) ?>">
                                            Devolver
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    } else {
                        echo '<div class="grid-row" style="text-align:center;"><div class="grid-cell" style="grid-column: 1 / -1;">No hay préstamos registrados</div></div>';
                    }
                    ?>
                </div>
            </div>
        </main>

        <!--    Cierre de Ventanas Emergentes    -->
    <?php elseif (isset($_SESSION['ci'])): ?>
        <?php include_once('./Complementos/nav.php') ?>
        <main>
            <div id="contenido-mostrar-datos">
                <div class="div-mostrar-datos">
                    <h1>Préstamos de Recursos</h1>
                </div>
                <div class="datos-grid prestamos-grid">
                    <!-- Cabecera -->
                    <div class="grid-header prestamos-header">
                        <div class="grid-cell nombre-titulo"><?= t("table_header_resource") ?></div>
                        <div class="grid-cell nombre-titulo"><?= t("table_header_start_time") ?></div>
                        <div class="grid-cell titulo-ult"><?= t("table_header_end_time") ?></div>
                    </div>

                    <!-- Filas de datos -->
                    <?php
                    if (mysqli_num_rows($prestamos_info2) > 0) {
                        while ($row = mysqli_fetch_array($prestamos_info2)):
                    ?>
                            <div class="grid-row prestamos-row mostrar-datos">
                                <div class="grid-cell"><?= $row['nombre_recurso'] ?></div>
                                <div class="grid-cell">
                                    <?= date('d/m/Y H:i:s', strtotime($row['hora_presta'])) ?>
                                </div>
                                <div class="grid-cell">
                                    <?php if ($row['hora_vuelta']): ?>
                                        <?= date('d/m/Y H:i:s', strtotime($row['hora_vuelta'])) ?>
                                    <?php else: ?>
                                        <span style="color: orange;"><?= t("status_not_returned") ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    } else {
                        echo '<div class="grid-row" style="text-align:center;"><div class="grid-cell" style="grid-column: 1 / -1;">No tienes préstamos registrados</div></div>';
                    }
                    ?>
                </div>

                <!-- Vista profesores celular -->
                <div class="flex-mostrar-datos">
                    <?php mysqli_data_seek($prestamos_info2, 0); ?>
                    <?php if (mysqli_num_rows($prestamos_info2) > 0) { ?>
                        <?php while ($row = mysqli_fetch_array($prestamos_info2)): ?>
                            <div class="datos-header-celu">
                                <?= toggle_mostrar_info($row['nombre_recurso']) ?>
                            </div>
                            <div class="informacion-escondida">
                                <div class="datos-tabla-flex">
                                    <div class="grid-cell">Hora de presta: <?= date('d/m/Y H:i:s', strtotime($row['hora_presta'])) ?></div>
                                    <div class="grid-cell">
                                        <?php if ($row['hora_vuelta']): ?>
                                            <?= date('d/m/Y H:i:s', strtotime($row['hora_vuelta'])) ?>
                                        <?php else: ?>
                                            <span style="color: orange;"><?= t("status_not_returned") ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    } else {
                        echo '<div class="grid-row" style="text-align:center;"><div class="grid-cell" style="grid-column: 1 / -1;">No tienes préstamos registrados</div></div>';
                    }
                    ?>
                </div>
            </div>
        </main>
    <?php elseif (!isset($_SESSION['ci'])): ?>
        <?php include_once('error.php') ?>
    <?php endif; ?>
    <?php include_once("./Complementos/footer.php") ?>
    <!-- Bootstrap -->
    <script type="module" src="../../backend/functions/Recursos/prestar-recursos/prestar.js"></script>
    <script type="module" src="../../backend/functions/Recursos/prestar-recursos/procesar_devolucion.js"></script>
    <script src="js/Register-Modal.js"></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mostrar informacion celular -->
    <script src="./js/Mostrar-informacion-general.js"></script>
    </body>