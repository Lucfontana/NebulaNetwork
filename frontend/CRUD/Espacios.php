<?php
include_once('../backend/db/conexion.php');
include_once('../backend/helpers.php');
include_once 'functions.php';

$result = mostrardatos("espacios_fisicos");
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
    <h1><?= t("title_physical_spaces") ?></h1>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
    </svg>
    </div>
    <div class="datos-grid espacios-grid">
        <!-- Cabecera -->
        <div class="grid-header espacios-header">
            <div class="grid-cell id">ID</div>
            <div class="grid-cell nombre-titulo"><?= t("header_capacity") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_name") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_type") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_delete") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_edit") ?></div>
        </div>

        <!-- Filas de datos -->
        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="grid-row espacios-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_espacio'] ?></div>
                <div class="grid-cell"><?= $row['capacidad'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell"><?= $row['tipo'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-espacio botones-datos"
                        data-id="<?= $row['id_espacio'] ?>">
                        <?= t("btn_delete") ?>
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-espacio botones-datos"
                        data-id="<?= $row['id_espacio'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-capacidad="<?= $row['capacidad'] ?>"
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
        <?php while ($row = mysqli_fetch_array($result)): $nombre = $row['nombre']?>
            <div class="datos-header-celu">
                <?php echo toggle_mostrar_info($nombre)?>
                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">ID: <?= $row['id_espacio'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_capacity") ?> <?= $row['capacidad'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_type") ?> <?= $row['tipo'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-espacio botones-datos"
                            data-id="<?= $row['id_espacio'] ?>">
                            <?= t("btn_delete") ?>
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-espacio botones-datos"
                            data-id="<?= $row['id_espacio'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-capacidad="<?= $row['capacidad'] ?>"
                            data-tipo="<?= $row['tipo'] ?>">
                            <?= t("btn_edit") ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para espacios -->
    <?php echo boton_eliminar("overlay-espacio", "el espacio", "confirmar-espacio", "cancelar-espacio")?>

    <!-- ID ÚNICO para editar espacio -->
    <div id="overlay-edit-espacio" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Espacio</h1>
            <form id="form-update-espacio">
                <input type="hidden" name="id_espacio" id="id_edit_espacio">

                <div class="input-group">
                    <label for="name_edit_espacio"><?= t("header_name") ?></label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_espacio"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="capacidad_edit_espacio"><?= t("header_capacity") ?></label>
                    <input class="class-datos-editar" type="text" name="capacidad" id="capacidad_edit_espacio"
                        maxlength="20" minlength="1" required placeholder="Ingresa capacidad">
                </div>

                <div class="input-group">
                    <label for="tipo_edit_espacio"><?= t("header_type") ?></label>
                    <select class="class-datos-editar" name="tipo" id="tipo_edit_espacio" required>
                        <option value=""><?= t("placeholder_select_type") ?></option>
                        <option value="aula"><?= t("option_aula") ?></option>
                        <option value="salon"><?= t("option_salon") ?></option>
                        <option value="laboratorio"><?= t("option_lab") ?></option>
                        <option value="SUM"><?= t("option_sum") ?></option>
                    </select>
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-espacio">
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>