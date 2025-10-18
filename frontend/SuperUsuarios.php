<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
$connect = conectar_a_bd();
$sql = "SELECT * FROM superusuario";
$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

    <h1>Super Usuarios</h1>

    <!-- Vista para PC -->
    <div class="datos-grid superusuario-grid">
        <div class="grid-header superusuario-header">
            <div class="grid-cell id">CI</div>
            <div class="grid-cell nombre-titulo">Nombre</div>
            <div class="grid-cell nombre-titulo">Apellido</div>
            <div class="grid-cell nombre-titulo">Nivel de Acceso</div>
            <div class="grid-cell nombre-titulo">Email</div>
            <div class="grid-cell boton-titulo">Eliminar</div>
            <div class="grid-cell boton-titulo">Editar</div>
        </div>

        <?php while ($row = mysqli_fetch_array($query)): ?>
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
                        Eliminar
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-super botones-datos"
                        data-id="<?= $row['id_superusuario'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-apellido="<?= $row['apellido'] ?>"
                        data-nivel="<?= $row['nivel_acceso'] ?>"
                        data-email="<?= $row['email_superusuario'] ?>">
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
                        <button class="mostrar-informacion-oculta">🔽</button>
                    </div>
                </div>

                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">CI: <?= $row['id_superusuario'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Nivel de Acceso: <?= $row['nivel_acceso'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Email: <?= $row['email_superusuario'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-super botones-datos"
                            data-id="<?= $row['id_superusuario'] ?>">
                            Eliminar
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-super botones-datos"
                            data-id="<?= $row['id_superusuario'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-apellido="<?= $row['apellido'] ?>"
                            data-nivel="<?= $row['nivel_acceso'] ?>"
                            data-email="<?= $row['email_superusuario'] ?>">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID único para eliminar -->
    <div class="overlay" id="overlay-super">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el registro de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn btn-confirmar" id="confirmar-super">Eliminar</button>
                <button class="btn btn-cancelar" id="cancelar-super">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- ID único para editar -->
    <div id="overlay-edit-super" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Super Usuario</h1>
            <form action="/backend/functions/SuperUsuarios/edit.php" method="POST" id="form-update-super">
                <input type="hidden" name="id_superusuario" id="id_edit_super">

                <div class="input-group">
                    <label for="nombre">Nombre:</label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_super"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="apellido">Apellido:</label>
                    <input class="class-datos-editar" type="text" name="apellido" id="apellido_edit_super"
                        maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                </div>

                <div class="input-group">
                    <label for="nivelacceso">Nivel de Acceso:</label>
                    <select class="class-datos-editar" name="nivelacceso" id="nivel_edit_super" required>
                        <option value=""></option>
                        <option value="1">1 - Adscripta</option>
                        <option value="2">2 - Secretaría</option>
                        <option value="3">3 - Administrador</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="email">Email:</label>
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