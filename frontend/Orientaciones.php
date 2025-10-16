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

    <div class="overlay" id="overlay-orientacion">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el registro de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn btn-confirmar" id="confirmar-orientacion" href="backend/functions/orientacion/delete.php?id=<?= $row['id_asignatura'] ?>">Eliminar</button>
                <button class="btn btn-cancelar" id="cancelar-orientacion">Cancelar</button>
            </div>
        </div>
    </div>


    <div id="overlay-edit-orientacion" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Orientación</h1>
            <form action="\backend\functions\Orientaciones\edit.php" method="POST" id="form-update-orientacion">
                <div class="div-labels">
                    <input class="input-register" type="hidden" name="id_orientacion" id="id_edit_orientacion">
                </div>
                <div class="input-group">
                    <label for="nombre">Nombre:</label>
                    <div>
                        <input class="class-datos-editar" type="text" name="nombre" id="name_edit_orientacion" maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                    </div>
                </div>
                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Infomacion" class="btn-primary" id="actualizar"></input>
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-orientacion"></input>
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>