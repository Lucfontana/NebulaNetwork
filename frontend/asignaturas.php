<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM asignaturas";

$query = mysqli_query($connect, $sql);

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style/style.css">

<?php if (!isset($_SESSION['nivel_acceso'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>

    <body>
        <!-- trae las barras de navegacion (sidebar y superior) -->
        <?php include 'nav.php'; ?>

        <main>
            <div id="contenido-mostrar-datos">
                <h1>Asignaturas</h1>

                <div class="datos-grid asignaturas-grid">
                    <!-- Cabecera -->
                    <div class="grid-header asignaturas-header">
                        <div class="grid-cell id">ID</div>
                        <div class="grid-cell nombre-titulo">Nombre</div>
                        <div class="grid-cell boton-titulo">Eliminar</div>
                        <div class="grid-cell boton-titulo">Editar</div>
                    </div>

                    <!-- Filas de datos -->
                    <?php while ($row = mysqli_fetch_array($query)): ?>
                        <div class="grid-row asignaturas-row mostrar-datos">
                            <div class="grid-cell"><?= $row['id_asignatura'] ?></div>
                            <div class="grid-cell"><?= $row['nombre'] ?></div>
                            <div class="grid-cell">
                                <a href="#"
                                    class="boton-datos-eliminar botones-datos"
                                    data-id="<?= $row['id_asignatura'] ?>">
                                    Eliminar
                                </a>
                            </div>
                            <div class="grid-cell">
                                <a class="boton-datos-editar botones-datos"
                                    data-id="<?= $row['id_asignatura'] ?>"
                                    data-nombre="<?= $row['nombre'] ?>">
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
                        <button class="btn btn-confirmar" id="confirmar" href="backend/functions/asignaturas/delete.php?id=<?= $row['id_asignatura'] ?>">Eliminar</button>
                        <button class="btn btn-cancelar" id="cancelar">Cancelar</button>
                    </div>
                </div>
            </div>


            <div id="overlay-edit" class="overlay-edit">
                <div class="popup">
                    <h1>Modificación de Asignatura</h1>
                    <form action="\backend\functions\asignaturas\edit.php" method="POST" id="form-update">
                        <div class="div-labels">
                            <input class="input-register" type="hidden" name="id_asignatura" id="id_edit">
                        </div>
                        <div class="input-group">
                            <label for="nombre">Nombre:</label>
                            <div>
                                <input class="class-datos-editar" type="text" name="nombre" id="name_edit" maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                            </div>
                        </div>
                        <div class="buttons-modal">
                            <input type="submit" value="Actualizar Infomacion" class="btn-primary" id="actualizar"></input>
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
    <script type="module" src="/frontend/js/confirm-asignatura.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
    </body>

</html>