<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_once('../backend/helpers.php');

$result = mostrardatos("profesores");
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
    <h1><?= t("header_teachers") ?></h1>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
    </svg>
    </div>
    <!-- Vista para PC -->
    <div class="datos-grid profesores-grid">
        <div class="grid-header profesores-header">
            <div class="grid-cell id"><?= t("label_ci") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_name") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_lastname") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_email") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_birth") ?></div>
            <div class="grid-cell nombre-titulo"><?= t("header_address") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_delete") ?></div>
            <div class="grid-cell boton-titulo"><?= t("btn_edit") ?></div>
        </div>

        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="grid-row profesores-row mostrar-datos">
                <div class="grid-cell"><?= $row['ci_profesor'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell"><?= $row['apellido'] ?></div>
                <div class="grid-cell"><?= $row['email'] ?></div>
                <div class="grid-cell"><?= $row['fecha_nac'] ?></div>
                <div class="grid-cell"><?= $row['direccion'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-profesor botones-datos"
                        data-id="<?= $row['ci_profesor'] ?>">
                        <?= t("btn_delete") ?>
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-profesor botones-datos"
                        data-id="<?= $row['ci_profesor'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-apellido="<?= $row['apellido'] ?>"
                        data-email="<?= $row['email'] ?>"
                        data-fnac="<?= $row['fecha_nac'] ?>"
                        data-direccion="<?= $row['direccion'] ?>">
                        <?= t("btn_edit") ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Vista para celular -->
    <?php mysqli_data_seek($result, 0); ?>
    <div class="flex-mostrar-datos">
        <?php while ($row = mysqli_fetch_array($result)): $nombre = $row['nombre'] . ' ' . $row['apellido'] ?>
            <div class="datos-header-celu">
                <?php echo toggle_mostrar_info($nombre)?>

                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_ci") ?> <?= $row['ci_profesor'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_email") ?> <?= $row['email'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("header_birth") ?> <?= $row['fecha_nac'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell"><?= t("label_address") ?> <?= $row['direccion'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-profesor botones-datos"
                            data-id="<?= $row['ci_profesor'] ?>">
                           <?= t("btn_delete") ?>

                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-profesor botones-datos"
                            data-id="<?= $row['ci_profesor'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-apellido="<?= $row['apellido'] ?>"
                            data-email="<?= $row['email'] ?>"
                            data-fnac="<?= $row['fecha_nac'] ?>"
                            data-direccion="<?= $row['direccion'] ?>">
                            <?= t("btn_edit") ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Overlay de confirmación -->
     <?php echo boton_eliminar('overlay-profesor', 'el registro', 'confirmar-profesor', 'cancelar-profesor')?>

    <!-- Popup de edición -->
    <div id="overlay-edit-profesor" class="overlay-edit">
        <div class="popup">
            <h1><?= t("title_edit_teacher") ?></h1>
            <form id="form-update-profesores">
                <input type="hidden" name="ci_profesor" id="id_edit_profesor">

                <div class="input-group">
                    <label for="name_edit_profesor"><?= t("label_name") ?></label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_profesor"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="apellido_edit_profesor"><?= t("label_lastname") ?></label>
                    <input class="class-datos-editar" type="text" name="apellido" id="apellido_edit_profesor"
                        maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                </div>

                <div class="input-group">
                    <label for="email_edit_profesor"><?= t("label_email") ?></label>
                    <input class="class-datos-editar" type="email" name="email" id="email_edit_profesor"
                        maxlength="40" minlength="5" required placeholder="Ingresa email">
                </div>

                <div class="input-group">
                    <label for="fnac_edit_profesor"><?= t("label_birth") ?></label>
                    <input class="class-datos-editar" type="date" name="fnac" id="fnac_edit_profesor" required>
                </div>

                <div class="input-group">
                    <label for="direccion_edit_profesor"><?= t("label_address") ?></label>
                    <input class="class-datos-editar" type="text" name="direccion" id="direccion_edit_profesor"
                        maxlength="50" minlength="3" required placeholder="Ingresa dirección">
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-profesor">
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>