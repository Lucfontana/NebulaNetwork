<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_once ('../backend/helpers.php');
include_once('../backend/queries.php');

$result = mostrardatos("asignaturas");
?>

<!-- Verificamos que el usuario sea un superusuario -->
<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
        <h1><?= t("option_subjects") ?></h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
        </svg>
    </div>
    <!-- Vista para PC - en forma de grid -->
    <div class="datos-grid asignaturas-grid">
        <div class="grid-header asignaturas-header">
            <div class="grid-cell id">ID</div>
            <div class="grid-cell nombre-titulo"><?= t("header_name") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_delete") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_edit") ?></div>
        </div>

        <!-- Creamos que un while que recorra el array creado en $result, en donde cargamos los datos de la posción en la que nos encontramos -->
        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="grid-row asignaturas-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_asignatura'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell">
                    <!-- Clase específica para asignaturas -->
                    <a class="boton-datos-eliminar boton-eliminar-asignatura botones-datos"
                        data-id="<?= $row['id_asignatura'] ?>"> <!-- le cargamos el id_asignatura para que al eliminar tenga un valor de referencia -->
                        <?= t("btn_delete") ?>
                    </a>
                </div>
                <div class="grid-cell">
                    <!-- Clase específica para asignaturas -->
                    <a class="boton-datos-editar boton-editar-asignatura botones-datos"
                        data-id="<?= $row['id_asignatura'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"> <!-- le cargamos los datos de data para que al editar se carguen los datos en los inputs  -->
                        <?= t("btn_edit") ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Vista para celular -->
    <!-- Marcamos que el array $result empiece en la posición 0 para que los datos se carguen correctamente -->
    <?php mysqli_data_seek($result, 0); ?>
    <div class="flex-mostrar-datos">
        <?php while ($row = mysqli_fetch_array($result)): $nombre = $row['nombre'] ?> <!-- Creamos un while que vaya pasando por los datos del array $result -->
            <div class="datos-header-celu">
                <?= toggle_mostrar_info($nombre)?> <!-- Cargamos la función y le pasamos el vaor de arriba -->
                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">ID: <?= $row['id_asignatura'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <!-- Clase específica para asignaturas -->
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-asignatura botones-datos"
                            data-id="<?= $row['id_asignatura'] ?>">
                            <?= t("btn_delete") ?>
                        </a>
                    </div>
                    <div class="grid-cell">
                        <!-- Clase específica para asignaturas -->
                        <a class="boton-datos-editar boton-editar-asignatura botones-datos"
                            data-id="<?= $row['id_asignatura'] ?>"
                            data-nombre="<?= $row['nombre'] ?>">
                            <?= t("btn_edit") ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para asignaturas -->
    <!-- cargamos la función que muestra el modal de eliminar -->
    <?php echo boton_eliminar("overlay-asignatura", "la asignatura", "confirmar-asignatura", "cancelar-asignatura")?>

    <!-- ID ÚNICO para editar asignatura -->
    <!-- Cargamos el modal de editar registro -->
    <div id="overlay-edit-asignatura" class="overlay-edit">
        <div class="popup">
            <h1><?= t("title_edit_subject") ?></h1>
            <form action="\backend\functions\asignaturas\edit.php" method="POST" id="form-update-asignatura">
                <input type="hidden" name="id_asignatura" id="id_edit_asignatura">

                <div class="input-group">
                    <label for="name_edit_asignatura"><?= t("label_name") ?></label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_asignatura"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-asignatura">
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>