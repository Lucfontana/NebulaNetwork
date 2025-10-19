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
    <div class="div-mostrar-datos">
        <h1>Asignaturas</h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
        </svg>
    </div>
    <!-- Vista para PC -->
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

    <!-- Vista para celular -->
    <?php mysqli_data_seek($query, 0); ?>
    <div class="flex-mostrar-datos">
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="datos-header-celu">
                <div class="datos-tabla-flex">
                    <div class="nombre-titulo grid-cell flex-header"><?= $row['nombre'] ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill mostrar-informacion-oculta" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill icono-guardar-informacion" viewBox="0 0 16 16">
                            <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
                        </svg>
                    </div>
                </div>
                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">ID: <?= $row['id_asignatura'] ?></div>
                    </div>

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
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para asignaturas -->
    <div class="overlay" id="overlay-asignatura">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará la asignatura de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn-confirmar" id="confirmar-asignatura">Eliminar</button>
                <button class="btn-cancelar" id="cancelar-asignatura">Cancelar</button>
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