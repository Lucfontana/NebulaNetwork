<?php
include_once('../backend/db/conexion.php');
$connect = conectar_a_bd();
$sql = "SELECT * FROM cursos";
$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
    <h1>Cursos</h1>
    </div>
    <!-- Vista para computadora -->
    <div class="datos-grid">
        <div class="grid-header">
            <div class="grid-cell id">Id</div>
            <div class="grid-cell nombre-titulo">Nombre</div>
            <div class="grid-cell nombre-titulo">Capacidad</div>
            <div class="grid-cell boton-titulo">Eliminar</div>
            <div class="grid-cell boton-titulo">Editar</div>
        </div>

        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="grid-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_curso'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell ultimo-dato"><?= $row['capacidad'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-curso botones-datos"
                        data-id="<?= $row['id_curso'] ?>">
                        Eliminar
                    </a>
                </div>
                <div class="grid-cell boton-dato">
                    <a class="boton-datos-editar boton-editar-curso botones-datos"
                        data-id="<?= $row['id_curso'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-capacidad="<?= $row['capacidad'] ?>">
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
                    <div class="nombre-titulo grid-cell flex-header">
                        <?= $row['nombre'] ?>
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
                        <div class="grid-cell">ID: <?= $row['id_curso'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Capacidad: <?= $row['capacidad'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-curso botones-datos"
                            data-id="<?= $row['id_curso'] ?>">
                            Eliminar
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-curso botones-datos"
                            data-id="<?= $row['id_curso'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-capacidad="<?= $row['capacidad'] ?>">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para cursos -->
    <div class="overlay" id="overlay-curso">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el curso de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn btn-confirmar" id="confirmar-curso">Eliminar</button>
                <button class="btn btn-cancelar" id="cancelar-curso">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- ID ÚNICO para editar curso -->
    <div id="overlay-edit-curso" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Curso</h1>
            <form action="/backend/functions/Cursos/edit.php" method="POST" id="form-update-curso">
                <input type="hidden" name="id_curso" id="id_edit_curso">

                <div class="input-group">
                    <label for="name_edit_curso">Nombre:</label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_curso"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="capacidad_edit_curso">Capacidad:</label>
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