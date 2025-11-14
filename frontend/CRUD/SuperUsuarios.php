<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_once '../backend/helpers.php';

$result = mostrardatos("superusuario");
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
    <h1><?= t("header_superusers") ?></h1>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
    </svg>
    </div>

    <!-- Vista para PC -->
    <div class="datos-grid superusuario-grid">
        <div class="grid-header superusuario-header">
            <div class="grid-cell id"><?= t("header_ci") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_name") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_lastname") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_access_level") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_email") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_delete") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_edit") ?></div>
        </div>

        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="grid-row superusuario-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_superusuario'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell"><?= $row['apellido'] ?></div>
                <div class="grid-cell"><?= $row['nivel_acceso'] ?></div>
                <div class="grid-cell"><?= $row['email_superusuario'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-super botones-datos"
                        data-id="<?= $row['id_superusuario'] ?>">
                        <?= t("btn_delete") ?>
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-super botones-datos"
                        data-id="<?= $row['id_superusuario'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-apellido="<?= $row['apellido'] ?>"
                        data-nivel="<?= $row['nivel_acceso'] ?>"
                        data-email="<?= $row['email_superusuario'] ?>">
                        <?= t("btn_edit") ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Vista para celular -->
    <?php mysqli_data_seek($result, 0); ?>
    <div class="flex-mostrar-datos">
        <?php while ($row = mysqli_fetch_array($result)): $nombre = $row['nombre'] . ' ' . $row['apellido']?>
            <div class="datos-header-celu">
                <?php echo toggle_mostrar_info($nombre)?>

                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_ci") ?> <?= $row['id_superusuario'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_access_level") ?> <?= $row['nivel_acceso'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_email") ?> <?= $row['email_superusuario'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-super botones-datos"
                            data-id="<?= $row['id_superusuario'] ?>">
                            <?= t("btn_delete") ?>
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-super botones-datos"
                            data-id="<?= $row['id_superusuario'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-apellido="<?= $row['apellido'] ?>"
                            data-nivel="<?= $row['nivel_acceso'] ?>"
                            data-email="<?= $row['email_superusuario'] ?>">
                            <?= t("btn_edit") ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID único para eliminar -->
     <?php boton_eliminar('overlay-super', 'el registro', 'confirmar-super', 'cancelar-super')?>

    <!-- ID único para editar -->
    <div id="overlay-edit-super" class="overlay-edit">
        <div class="popup">
            <h1><?= t("title_edit_superuser") ?></h1>
            <form id="form-update-super">
                <input type="hidden" name="id_superusuario" id="id_edit_super">

                <div class="input-group">
                    <label for="nombre"><?= t("header_name") ?></label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_super"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="apellido"><?= t("header_lastname") ?></label>
                    <input class="class-datos-editar" type="text" name="apellido" id="apellido_edit_super"
                        maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                </div>

                <div class="input-group">
                    <label for="nivelacceso"><?= t("header_access_level") ?></label>
                    <select class="class-datos-editar" name="nivelacceso" id="nivel_edit_super" required>
                        <option value=""></option>
                        <option value="1"><?= t("option_access_1") ?></option>
                        <option value="2"><?= t("option_access_2") ?></option>
                        <option value="3"><?= t("option_access_3") ?></option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="email"><?= t("header_email") ?>:</label>
                    <input type="email" class="class-datos-editar" name="email" id="email_edit_super" required>
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary" id="actualizar-super">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-super">
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>