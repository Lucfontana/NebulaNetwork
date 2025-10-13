<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM cursos";

$query = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style/style.css">

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>
    <main>
        <div id="contenido-mostrar-datos">
            <h1>Cursos</h1>

            <div class="datos-grid">
                <!-- Cabecera -->
                <div class="grid-header">
                    <div class="grid-cell id">Id</div>
                    <div class="grid-cell nombre-titulo">Nombre</div>
                    <div class="grid-cell nombre-titulo">Capacidad</div>
                    <div class="grid-cell boton-titulo">Eliminar</div>
                    <div class="grid-cell boton-titulo">Editar</div>
                </div>

                <!-- Datos -->
                <?php while ($row = mysqli_fetch_array($query)): ?>
                    <div class="grid-row mostrar-datos">
                        <div class="grid-cell"><?= $row['id_curso'] ?></div>
                        <div class="grid-cell"><?= $row['nombre'] ?></div>
                        <div class="grid-cell ultimo-dato"><?= $row['capacidad'] ?></div>
                        <div class="grid-cell">
                            <a href="#" class="boton-datos-eliminar botones-datos"
                                data-id="<?= $row['id_curso'] ?>">
                                Eliminar
                            </a>
                        </div>
                        <div class="grid-cell boton-dato">
                            <a class="boton-datos-editar botones-datos"
                                data-id="<?= $row['id_curso'] ?>"
                                data-nombre="<?= $row['nombre'] ?>"
                                data-capacidad="<?= $row['capacidad'] ?>">
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
                    <button class="btn btn-confirmar" id="confirmar" href="backend/functions/Cursos/delete.php?id=<?= $row['id_curso'] ?>">Eliminar</button>
                    <button class="btn btn-cancelar" id="cancelar">Cancelar</button>
                </div>
            </div>
        </div>


        <div id="overlay-edit" class="overlay-edit">
            <div class="popup">
                <h1>Modificación de Curso</h1>
                <form action="/backend/functions/Cursos/edit.php" method="POST" id="form-update">
                    <div class="div-labels">
                        <input class="input-register" type="hidden" name="id_curso" id="id_edit">
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
                            <input class="class-datos-editar" type="number" name="capacidad" id="capacidad_edit" maxlength="3" minlength="1" required placeholder="Ingresa capacidad">
                        </div>
                    </div>

                    <div class="buttons-modal">
                        <input type="submit" value="Actualizar Información" class="btn-primary actualizar" id="actualizar"></input>
                        <input type="button" value="Cancelar" class="btn-secondary" id="cancelarEdit"></input>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script type="module" src="/frontend/js/confirm-cursos.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
</body>

</html>