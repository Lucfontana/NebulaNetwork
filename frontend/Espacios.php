<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM espacios_fisicos";

$query = mysqli_query($connect, $sql);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<title>Espacios Fisicos</title>
 <!-- trae las barras de navegacion (sidebar y superior) -->
<?php include 'nav.php'; ?>
<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

<body>
    <main>
        <div id="contenido-mostrar-datos">
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
                                class="boton-datos-eliminar botones-datos"
                                data-id="<?= $row['id_espacio'] ?>">
                                Eliminar
                            </a>
                        </div>
                        <div class="grid-cell">
                            <a class="boton-datos-editar botones-datos"
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
        </div>

        <div class="overlay" id="overlay">
            <div class="confirmacion">
                <h2>¿Estás seguro?</h2>
                <p>Esta acción eliminará el registro de forma permanente.</p>
                <div class="botones_confirmar">
                    <button class="btn-primary" id="confirmar">Eliminar</button>
                    <button class="btn-secondary" id="cancelar">Cancelar</button>
                </div>
            </div>
        </div>


        <div id="overlay-edit" class="overlay-edit">
            <div class="popup">
                <h1>Modificación de Espacio</h1>
                <form action="\backend\functions\Espacios\edit.php" method="POST" id="form-update">
                    <div class="div-labels">
                        <input class="input-register" type="hidden" name="id_espacio" id="id_edit">
                    </div>

                    <div class="input-group">
                        <label for="nombre">Nombre:</label>
                        <div>
                            <input class="class-datos-editar" type="text" name="nombre" id="name_edit" maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="capacidad">Capacidad:</label>
                        <div>
                            <input class="class-datos-editar" type="text" name="capacidad" id="capacidad_edit" maxlength="20" minlength="1" required placeholder="Ingresa capacidad">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="tipo">Tipo:</label>
                        <div>
                            <select class="class-datos-editar" name="tipo" id="tipo_edit" required>
                                <option value=""></option>
                                <option value="aula">Aula</option>
                                <option value="salon">Salón</option>
                                <option value="laboratorio">Laboratorio</option>
                                <option value="SUM">SUM</option>
                            </select>
                        </div>
                    </div>

                    <div class="buttons-modal">
                        <input type="submit" value="Actualizar Información" class="btn-primary" id="actualizar"></input>
                        <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit"></input>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>
    <?php endif; ?>
    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script type="module" src="/frontend/js/confirm-espacios.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
</body>

</html>