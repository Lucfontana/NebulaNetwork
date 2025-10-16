<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
$connect = conectar_a_bd();
$sql = "SELECT * FROM asignaturas";
$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <h1>Asignaturas</h1>
    <div class="datos-grid asignaturas-grid">
        <div class="grid-header asignaturas-header">
            <div class="grid-cell id">ID</div>
            <div class="grid-cell nombre-titulo">Nombre</div>
            <div class="grid-cell boton-titulo">Eliminar</div>
            <div class="grid-cell boton-titulo">Editar</div>
        </div>

        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="grid-row asignaturas-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_asignatura'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell">
                    <!-- Clase específica para asignaturas -->
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-asignatura botones-datos"
                        data-id="<?= $row['id_asignatura'] ?>">
                        Eliminar
                    </a>
                </div>
                <div class="grid-cell">
                    <!-- Clase específica para asignaturas -->
                    <a class="boton-datos-editar boton-editar-asignatura botones-datos"
                        data-id="<?= $row['id_asignatura'] ?>"
                        data-nombre="<?= $row['nombre'] ?>">
                        Editar
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para asignaturas -->
    <div class="overlay" id="overlay-asignatura">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará la asignatura de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn btn-confirmar" id="confirmar-asignatura">Eliminar</button>
                <button class="btn btn-cancelar" id="cancelar-asignatura">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- ID ÚNICO para editar asignatura -->
    <div id="overlay-edit-asignatura" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Asignatura</h1>
            <form action="\backend\functions\asignaturas\edit.php" method="POST" id="form-update-asignatura">
                <input type="hidden" name="id_asignatura" id="id_edit_asignatura">
                
                <div class="input-group">
                    <label for="name_edit_asignatura">Nombre:</label>
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