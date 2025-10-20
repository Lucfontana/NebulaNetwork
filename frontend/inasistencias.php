<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_once('../backend/helpers.php');
$connect = conectar_a_bd();

$sql = "SELECT * FROM inasistencia";
$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
        <h1>Inasistencias</h1>
    </div>

    <!-- Vista para PC -->
    <div class="datos-grid inasistencias-grid">
        <div class="grid-header inasistencias-header">
            <div class="grid-cell id">ID</div>
            <div class="grid-cell nombre-titulo">Fecha</div>
            <div class="grid-cell nombre-titulo">CI Profesor</div>
            <div class="grid-cell nombre-titulo">Horario</div>
            <div class="grid-cell boton-titulo">Eliminar</div>
            <div class="grid-cell boton-titulo">Editar</div>
        </div>

        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="grid-row inasistencias-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_inasistencia'] ?></div>
                <div class="grid-cell"><?= $row['fecha_inasistencia'] ?></div>
                <div class="grid-cell"><?= $row['ci_profesor'] ?></div>
                <div class="grid-cell"><?= $row['id_horario'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-inasistencia botones-datos"
                        data-id="<?= $row['id_inasistencia'] ?>">
                        Eliminar
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-inasistencia botones-datos"
                        data-id="<?= $row['id_inasistencia'] ?>"
                        data-fecha="<?= $row['fecha_inasistencia'] ?>"
                        data-profesor="<?= $row['ci_profesor'] ?>"
                        data-horario="<?= $row['id_horario'] ?>">
                        Editar
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Vista para celular -->
    <?php mysqli_data_seek($query, 0); ?>
    <div class="flex-mostrar-datos">
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <?php $titulo = "Inasistencia #" . $row['id_inasistencia']; ?>
            <div class="datos-header-celu">
                <?= toggle_mostrar_info($titulo) ?>
                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Fecha: <?= $row['fecha_inasistencia'] ?></div>
                        <div class="grid-cell">CI Profesor: <?= $row['ci_profesor'] ?></div>
                        <div class="grid-cell">Horario: <?= $row['id_horario'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-inasistencia botones-datos"
                            data-id="<?= $row['id_inasistencia'] ?>">
                            Eliminar
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-inasistencia botones-datos"
                            data-id="<?= $row['id_inasistencia'] ?>"
                            data-fecha="<?= $row['fecha_inasistencia'] ?>"
                            data-profesor="<?= $row['ci_profesor'] ?>"
                            data-horario="<?= $row['id_horario'] ?>">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para inasistencias -->
    <?php echo boton_eliminar("overlay-inasistencia", "la inasistencia", "confirmar-inasistencia", "cancelar-inasistencia") ?>

    <!-- ID ÚNICO para editar inasistencia -->
    <div id="overlay-edit-inasistencia" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Inasistencia</h1>
            <form action="\backend\functions\inasistencias\edit.php" method="POST" id="form-update-inasistencia">
                <input type="hidden" name="id_inasistencia" id="id_edit_inasistencia">

                <div class="input-group">
                    <label for="fecha_edit_inasistencia">Fecha:</label>
                    <input class="class-datos-editar" type="date" name="fecha_inasistencia"
                        id="fecha_edit_inasistencia" required>
                </div>

                <div class="input-group">
                    <label for="horario_edit_inasistencia">Horario:</label>
                    <input class="class-datos-editar" type="text" name="id_horario"
                        id="horario_edit_inasistencia" required>
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-inasistencia">
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>