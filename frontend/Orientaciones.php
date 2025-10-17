<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM orientacion";
$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

<h1>Orientaciones</h1>

<!-- VISTA PARA PC -->
<div class="datos-grid orientaciones-grid">
    <!-- Cabecera -->
    <div class="grid-header orientaciones-header">
        <div class="grid-cell id">ID</div>
        <div class="grid-cell nombre-titulo">Nombre</div>
        <div class="grid-cell boton-titulo">Eliminar</div>
        <div class="grid-cell boton-titulo">Editar</div>
    </div>

    <!-- Filas de datos -->
    <?php while ($row = mysqli_fetch_array($query)): ?>
        <div class="grid-row orientaciones-row mostrar-datos">
            <div class="grid-cell"><?= $row['id_orientacion'] ?></div>
            <div class="grid-cell"><?= $row['nombre'] ?></div>
            <div class="grid-cell">
                <a href="#"
                   class="boton-datos-eliminar boton-eliminar-orientacion botones-datos"
                   data-id="<?= $row['id_orientacion'] ?>">
                   Eliminar
                </a>
            </div>
            <div class="grid-cell">
                <a class="boton-datos-editar boton-editar-orientacion botones-datos"
                   data-id="<?= $row['id_orientacion'] ?>"
                   data-nombre="<?= $row['nombre'] ?>">
                   Editar
                </a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- VISTA PARA CELULAR -->
<?php mysqli_data_seek($query, 0); ?>
<div class="flex-mostrar-datos">
    <?php while ($row = mysqli_fetch_array($query)): ?>
        <div class="datos-header-celu">
            <div class="datos-tabla-flex">
                <div class="nombre-titulo grid-cell flex-header">
                    <?= $row['nombre'] ?> 
                    <button class="mostrar-informacion-oculta">🔽</button>
                </div>
            </div>
            <div class="informacion-escondida">
                <div class="datos-tabla-flex">
                    <div class="grid-cell">ID: <?= $row['id_orientacion'] ?></div>
                </div>

                <div class="grid-cell">
                    <a href="#"
                       class="boton-datos-eliminar boton-eliminar-orientacion botones-datos"
                       data-id="<?= $row['id_orientacion'] ?>">
                       Eliminar
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-orientacion botones-datos"
                       data-id="<?= $row['id_orientacion'] ?>"
                       data-nombre="<?= $row['nombre'] ?>">
                       Editar
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- OVERLAY DE CONFIRMACIÓN -->
<div class="overlay" id="overlay-orientacion">
    <div class="confirmacion">
        <h2>¿Estás seguro?</h2>
        <p>Esta acción eliminará el registro de forma permanente.</p>
        <div class="botones_confirmar">
            <button class="btn btn-confirmar" id="confirmar-orientacion">Eliminar</button>
            <button class="btn btn-cancelar" id="cancelar-orientacion">Cancelar</button>
        </div>
    </div>
</div>

<!-- OVERLAY DE EDICIÓN -->
<div id="overlay-edit-orientacion" class="overlay-edit">
    <div class="popup">
        <h1>Modificación de Orientación</h1>
        <form action="\backend\functions\Orientaciones\edit.php" method="POST" id="form-update-orientacion">
            <input type="hidden" name="id_orientacion" id="id_edit_orientacion">

            <div class="input-group">
                <label for="nombre">Nombre:</label>
                <input class="class-datos-editar" type="text" name="nombre" id="name_edit_orientacion"
                       maxlength="20" minlength="3" required placeholder="Ingresa nombre">
            </div>

            <div class="buttons-modal">
                <input type="submit" value="Actualizar Información" class="btn-primary" id="actualizar">
                <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-orientacion">
            </div>
        </form>
    </div>
</div>

<?php endif; ?>