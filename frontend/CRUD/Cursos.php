<?php
include_once('../backend/db/conexion.php');
include_once('../backend/helpers.php');
include_once 'functions.php';

$result = mostrardatos("cursos");
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
    <h1>Cursos</h1>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
    </svg>
    </div>
    <!-- Vista para computadora -->
    <div class="datos-grid">
        <div class="grid-header">
            <div class="grid-cell id">Id</div>
            <div class="grid-cell nombre-titulo"><?= t('header_name') ?></div>
            <div class="grid-cell nombre-titulo"><?= t('header_capacity') ?></div>
            <div class="grid-cell boton-titulo"><?= t('btn_delete') ?></div>
            <div class="grid-cell boton-titulo"><?= t('btn_edit') ?></div>
        </div>

        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="grid-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_curso'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell ultimo-dato"><?= $row['capacidad'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-curso botones-datos"
                        data-id="<?= $row['id_curso'] ?>">
                        <?= t('btn_delete') ?>
                    </a>
                </div>
                <div class="grid-cell boton-dato">
                    <a class="boton-datos-editar boton-editar-curso botones-datos"
                        data-id="<?= $row['id_curso'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-capacidad="<?= $row['capacidad'] ?>">
                         <?= t('btn_edit') ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Vista para celular -->
    <?php mysqli_data_seek($result, 0); ?>
    <div class="flex-mostrar-datos">
        <?php while ($row = mysqli_fetch_array($result)): $nombre = $row['nombre'] ?>
            <div class="datos-header-celu">
                <?= toggle_mostrar_info($nombre)?>
                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">ID: <?= $row['id_curso'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">"<?= t('placeholder_capacity') ?> <?= $row['capacidad'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-curso botones-datos"
                            data-id="<?= $row['id_curso'] ?>">
                            <?= t('btn_delete') ?>
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-curso botones-datos"
                            data-id="<?= $row['id_curso'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-capacidad="<?= $row['capacidad'] ?>">
                             <?= t('btn_edit') ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para cursos -->
    <?php echo boton_eliminar("overlay-curso", "el curso", "confirmar-curso", "cancelar-curso")?>

    <!-- ID ÚNICO para editar curso -->
    <div id="overlay-edit-curso" class="overlay-edit">
        <div class="popup">
            <h1><?= t('title_edit_course') ?></h1>
            <form action="/backend/functions/Cursos/edit.php" method="POST" id="form-update-curso">
                <input type="hidden" name="id_curso" id="id_edit_curso">

                <div class="input-group">
                    <label for="name_edit_curso"><?= t('label_name') ?></label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_curso"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="capacidad_edit_curso">"<?= t('placeholder_capacity') ?>":</label>
                    <input class="class-datos-editar" type="number" name="capacidad" id="capacidad_edit_curso"
                        maxlength="3" minlength="1" required placeholder="Ingresa capacidad">
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-curso">
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>