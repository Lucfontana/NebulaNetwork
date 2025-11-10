<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_once '../backend/helpers.php';

$result = mostrardatos("recursos");
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
    <h1><?= t("header_resources") ?></h1>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
    </svg>
    </div>
    <!-- Vista para PC -->
    <div class="datos-grid recursos-grid">
        <div class="grid-header recursos-header">
            <div class="grid-cell id">ID</div>
            <div class="grid-cell nombre-titulo"><?= t("label_physical_space") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_name") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("label_status") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_type") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_delete") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_edit") ?></div>
        </div>

        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="grid-row recursos-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_recurso'] ?></div>
                <div class="grid-cell"><?= $row['id_espacio'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell"><?= $row['estado'] ?></div>
                <div class="grid-cell"><?= $row['tipo'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-recurso botones-datos"
                        data-id="<?= $row['id_recurso'] ?>">
                        <?= t("btn_delete") ?>
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-recurso botones-datos"
                        data-id="<?= $row['id_recurso'] ?>"
                        data-espacio="<?= $row['id_espacio'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-estado="<?= $row['estado'] ?>"
                        data-tipo="<?= $row['tipo'] ?>">
                        <?= t("btn_edit") ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Vista para celular -->
    <?php mysqli_data_seek($result, 0); ?>
    <div class="flex-mostrar-datos">
        <?php while ($row = mysqli_fetch_array($result)): $nombre = $row['nombre']; ?>
            <div class="datos-header-celu">
                <?php echo toggle_mostrar_info($nombre)?>
                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">ID: <?= $row['id_recurso'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_physical_space") ?>: <?= $row['id_espacio'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_status") ?>: <?= $row['estado'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_type") ?> <?= $row['tipo'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-recurso botones-datos"
                            data-id="<?= $row['id_recurso'] ?>">
                            <?= t("btn_delete") ?>
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-recurso botones-datos"
                            data-id="<?= $row['id_recurso'] ?>"
                            data-espacio="<?= $row['id_espacio'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-estado="<?= $row['estado'] ?>"
                            data-tipo="<?= $row['tipo'] ?>">
                            <?= t("btn_edit") ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Overlay de confirmación -->
     <?php echo boton_eliminar('overlay-recurso', 'el recurso', 'confirmar-recurso', 'cancelar-recurso')?>

    <!-- Popup de edición -->
    <div id="overlay-edit-recurso" class="overlay-edit">
        <div class="popup">
            <h1><?= t("title_edit_resource") ?></h1>
            <form id="form-update-recurso">
                <div class="div-labels">
                    <input class="input-register" type="hidden" name="id_recurso" id="id_edit_recurso">
                    <input class="input-register" type="hidden" name="id_espacio" id="id_espacio_edit">
                </div>

                <div class="input-group">
                    <label for="nombre"><?= t("label_name") ?></label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_recurso"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="estado"><?= t("label_status") ?></label>
                    <select class="class-datos-editar" name="estado" id="estado_edit_recurso" required>
                        <option value=""></option>
                        <option value="uso"><?= t("option_status_in_use") ?></option>
                        <option value="libre"><?= t("option_status_free") ?></option>
                        <option value="roto"><?= t("option_status_broken") ?></option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="tipo"><?= t("label_type") ?></label>
                    <select class="class-datos-editar" name="tipo" id="tipo_edit_recurso" required>
                        <option value=""></option>
                        <option value="interno"><?= t("option_type_internal") ?></option>
                        <option value="externo"><?= t("option_type_external") ?></option>
                    </select>
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary" id="actualizar-recurso">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-recurso">
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>