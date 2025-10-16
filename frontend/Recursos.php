<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM recursos";

$query = mysqli_query($connect, $sql);
?>

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

    <h1>Recursos</h1>

    <div class="datos-grid recursos-grid">
        <!-- Cabecera -->
        <div class="grid-header recursos-header">
            <div class="grid-cell id">ID</div>
            <div class="grid-cell nombre-titulo">Espacio Físico</div>
            <div class="grid-cell nombre-titulo">Nombre</div>
            <div class="grid-cell nombre-titulo">Estado</div>
            <div class="grid-cell nombre-titulo">Tipo</div>
            <div class="grid-cell boton-titulo">Eliminar</div>
            <div class="grid-cell boton-titulo">Editar</div>
        </div>

        <!-- Filas de datos -->
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="grid-row recursos-row mostrar-datos">
                <div class="grid-cell"><?= $row['id_recurso'] ?></div>
                <div class="grid-cell"><?= $row['id_espacio'] ?></div>
                <div class="grid-cell"><?= $row['nombre'] ?></div>
                <div class="grid-cell"><?= $row['estado'] ?></div>
                <div class="grid-cell"><?= $row['tipo'] ?></div>
                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-recurso botones-datos"
                        data-id="<?= $row['id_recurso'] ?>">
                        Eliminar
                    </a>
                </div>
                <div class="grid-cell">
                    <a class="boton-datos-editar boton-editar-recurso botones-datos"
                        data-id="<?= $row['id_recurso'] ?>"
                        data-espacio="<?= $row['id_espacio'] ?>"
                        data-nombre="<?= $row['nombre'] ?>"
                        data-estado="<?= $row['estado'] ?>"
                        data-tipo="<?= $row['tipo'] ?>">
                        Editar
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="overlay" id="overlay-recurso">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el registro de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn btn-confirmar" id="confirmar-recurso">Eliminar</button>
                <button class="btn btn-cancelar" id="cancelar-recurso">Cancelar</button>
            </div>
        </div>
    </div>


    <div id="overlay-edit-recurso" class="overlay-edit">
        <div class="popup">
            <h1>Modificación de Recurso</h1>
            <form action="/backend/functions/Recursos/edit.php" method="POST" id="form-update-recurso">
                <div class="div-labels">
                    <input class="input-register" type="hidden" name="id_recurso" id="id_edit_recurso">
                    <input class="input-register" type="hidden" name="id_espacio" id="id_espacio_edit">
                </div>

                <div class="input-group">
                    <label for="nombre">Nombre:</label>
                    <div>
                        <input class="class-datos-editar" type="text" name="nombre" id="name_edit_recurso" maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                    </div>
                </div>

                <div class="input-group">
                    <label for="estado">Estado:</label>
                    <div>
                        <select class="class-datos-editar" name="estado" id="estado_edit_recurso" required>
                            <option value=""></option>
                            <option value="uso">Uso</option>
                            <option value="libre">Libre</option>
                            <option value="roto">Roto</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label for="tipo">Tipo:</label>
                    <div>
                        <select class="class-datos-editar" name="tipo" id="tipo_edit_recurso" required>
                            <option value=""></option>
                            <option value="interno">Interno</option>
                            <option value="externo">Externo</option>
                        </select>
                    </div>
                </div>

                <div class="buttons-modal">
                    <input type="submit" value="Actualizar Información" class="btn-primary" id="actualizar-recurso"></input>
                    <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit-recurso"></input>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>