<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM espacios_fisicos";
$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

    <h1>Espacios Físicos</h1>
    <div class="datos-grid espacios-grid">
        <!-- Cabecera -->
        <div class="grid-header espacios-header">
            <div class="grid-cell id">ID</div>
            <div class="grid-cell nombre-titulo">Capacidad</div>
            <div class="grid-cell nombre-titulo">Nombre</div>
            <div class="grid-cell nombre-titulo">Tipo</div>
            <div class="grid-cell boton-titulo">Eliminar</div>
            <div class="grid-cell boton-titulo">Editar</div>
        </div>

        <!-- Filas de datos -->
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="grid-row espacios-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_espacio'] ?></div>
                <div class="grid-cell"><?= $row['capacidad'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell"><?= $row['tipo'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-espacio botones-datos"
                        data-id="<?= $row['id_espacio'] ?>">
                        Eliminar
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-espacio botones-datos"
                        data-id="<?= $row['id_espacio'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-capacidad="<?= $row['capacidad'] ?>"
                        data-tipo="<?= $row['tipo'] ?>">
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
                        <button class="mostrar-informacion-oculta">🔽</button>
                    </div>
                </div>
                <div class="informacion-escondida">
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">ID: <?= $row['id_espacio'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Capacidad: <?= $row['capacidad'] ?></div>
                    </div>
                    <div class="datos-tabla-flex">
                        <div class="grid-cell">Tipo: <?= $row['tipo'] ?></div>
                    </div>

                    <div class="grid-cell">
                        <a href="#"
                            class="boton-datos-eliminar boton-eliminar-espacio botones-datos"
                            data-id="<?= $row['id_espacio'] ?>">
                            Eliminar
                        </a>
                    </div>
                    <div class="grid-cell">
                        <a class="boton-datos-editar boton-editar-espacio botones-datos"
                            data-id="<?= $row['id_espacio'] ?>"
                            data-nombre="<?= $row['nombre'] ?>"
                            data-capacidad="<?= $row['capacidad'] ?>"
                            data-tipo="<?= $row['tipo'] ?>">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ID ÚNICO para espacios -->
    <div class="overlay" id="overlay-espacio">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el espacio de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn-primary" id="confirmar-espacio">Eliminar</button>
                <button class="btn-secondary" id="cancelar-espacio">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- ID ÚNICO para editar espacio -->
    <div id="overlay-edit-espacio" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Espacio</h1>
            <form action="/backend/functions/Espacios/edit.php" method="POST" id="form-update-espacio">
                <input type="hidden" name="id_espacio" id="id_edit_espacio">

                <div class="input-group">
                    <label for="name_edit_espacio">Nombre:</label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit_espacio"
                        maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                </div>

                <div class="input-group">
                    <label for="capacidad_edit_espacio">Capacidad:</label>
                    <input class="class-datos-editar" type="text" name="capacidad" id="capacidad_edit_espacio"
                        maxlength="20" minlength="1" required placeholder="Ingresa capacidad">
                </div>

                <div class="input-group">
                    <label for="tipo_edit_espacio">Tipo:</label>
                    <select class="class-datos-editar" name="tipo" id="tipo_edit_espacio" required>
                        <option value="">Seleccione...</option>
                        <option value="aula">Aula</option>
                        <option value="salon">Salón</option>
                        <option value="laboratorio">Laboratorio</option>
                        <option value="SUM">SUM</option>
                    </select>
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary">
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-espacio">
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>