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
            <table id="datos">
                <tr>
                    <th class="id">Id </th>
                    <th class="nombre-titulo">Nombre</th>
                    <th class="nombre-titulo">Capacidad</th>
                    <th class="boton-titulo">Borrar</th>
                    <th class="boton-titulo">Editar</th>
                </tr>
                <?php while ($row = mysqli_fetch_array($query)): ?>
                    <tr class="mostrar-datos">
                        <th class="nombre"><?= $row['id_curso'] ?></th>
                        <th class="nombre"><?= $row['nombre'] ?></th>
                        <th class="ultimo-dato"><?= $row['capacidad'] ?></th>
                        <th class="boton-dato"><a href="#" class="boton-datos-eliminar botones-datos" data-id="<?= $row['id_curso'] ?>">Eliminar</a></th>
                        <th class="boton-dato"><a class="boton-datos-editar botones-datos" data-id="<?= $row['id_curso'] ?>" data-nombre="<?= $row['nombre'] ?>" data-capacidad="<?= $row['capacidad'] ?>">Editar</a></th>
                    </tr>
                <?php endwhile; ?>
            </table>
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
                <h1>Edición de Cursos</h1>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script type="module" src="/frontend/js/confirm-cursos.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
</body>

</html>