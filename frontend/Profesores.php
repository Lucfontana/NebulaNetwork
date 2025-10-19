<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
$connect = conectar_a_bd();
$sql = "SELECT * FROM profesores";
$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <div class="div-mostrar-datos">
    <h1>Profesores</h1>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill btn" data-toggle="modal" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
    </svg>
    </div>
    <!-- Vista para PC -->
    <div class="datos-grid profesores-grid">
        <div class="grid-header profesores-header">
            <div class="grid-cell id">CI</div>
            <div class="grid-cell nombre-titulo">Nombre</div>
            <div class="grid-cell nombre-titulo">Apellido</div>
            <div class="grid-cell nombre-titulo">Email</div>
            <div class="grid-cell nombre-titulo">F. Nac</div>
            <div class="grid-cell nombre-titulo">Dirección</div>
            <div class="grid-cell boton-titulo">Eliminar</div>
            <div class="grid-cell boton-titulo">Editar</div>
        </div>

        <?php while ($row = mysqli_fetch_array($query)): ?>
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
                        Eliminar
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
                        <?= $row['nombre'] . ' ' . $row['apellido'] ?> 
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
                        <div class="grid-cell">CI: <?= $row['ci_profesor'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Email: <?= $row['email'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">F. Nac: <?= $row['fecha_nac'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Dirección: <?= $row['direccion'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-profesor botones-datos"
                            data-id="<?= $row['ci_profesor'] ?>">
                            Eliminar
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
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Overlay de confirmación -->
    <div class="overlay" id="overlay-profesor">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el registro de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn-confirmar" id="confirmar-profesor">Eliminar</button>
                <button class="btn-cancelar" id="cancelar-profesor">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Popup de edición -->
    <div id="overlay-edit-profesor" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Profesor</h1>
            <form action="/backend/functions/Profesores/edit.php" method="POST" id="form-update-profesores">
                <input type="hidden" name="ci_profesor" id="id_edit_profesor">

                <div class="input-group">
                    <label for="name_edit_profesor">Nombre:</label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_profesor"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="apellido_edit_profesor">Apellido:</label>
                    <input class="class-datos-editar" type="text" name="apellido" id="apellido_edit_profesor"
                        maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                </div>

                <div class="input-group">
                    <label for="email_edit_profesor">Email:</label>
                    <input class="class-datos-editar" type="email" name="email" id="email_edit_profesor"
                        maxlength="40" minlength="5" required placeholder="Ingresa email">
                </div>

                <div class="input-group">
                    <label for="fnac_edit_profesor">Fecha de Nacimiento:</label>
                    <input class="class-datos-editar" type="date" name="fnac" id="fnac_edit_profesor" required>
                </div>

                <div class="input-group">
                    <label for="direccion_edit_profesor">Dirección:</label>
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