<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM profesores";

$query = mysqli_query($connect, $sql);
?>


<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

    <h1>Profesores</h1>
    <div class="datos-grid profesores-grid">
        <!-- Cabecera -->
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

        <!-- Filas de datos -->
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

    <div class="overlay" id="overlay-profesor">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el registro de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn btn-confirmar" id="confirmar-profesor" href="backend/functions/Profesores/delete.php?id=<?= $row['ci_profesor'] ?>">Eliminar</button>
                <button class="btn btn-cancelar" id="cancelar-profesor">Cancelar</button>
            </div>
        </div>
    </div>


    <div id="overlay-edit-profesor" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Profesor</h1>
            <form action="/backend/functions/Profesores/edit.php" method="POST" id="form-update-profesores">
                <div class="div-labels">
                    <input class="input-register" type="hidden" name="ci_profesor" id="id_edit_profesor">
                </div>

                <div class="input-group">
                    <label for="nombre">Nombre:</label>
                    <div>
                        <input class="class-datos-editar" type="text" name="nombre" id="name_edit_profesor" maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                    </div>
                </div>

                <div class="input-group">
                    <label for="apellido">Apellido:</label>
                    <div>
                        <input class="class-datos-editar" type="text" name="apellido" id="apellido_edit_profesor" maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email:</label>
                    <div>
                        <input class="class-datos-editar" type="email" name="email" id="email_edit_profesor" maxlength="40" minlength="5" required placeholder="Ingresa email">
                    </div>
                </div>

                <div class="input-group">
                    <label for="fnac">Fecha de Nacimiento:</label>
                    <div>
                        <input class="class-datos-editar" type="date" name="fnac" id="fnac_edit_profesor" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="direccion">Dirección:</label>
                    <div>
                        <input class="class-datos-editar" type="text" name="direccion" id="direccion_edit_profesor" maxlength="50" minlength="3" required placeholder="Ingresa dirección">
                    </div>
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary" id="actualizar"></input>
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-profesor"></input>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>