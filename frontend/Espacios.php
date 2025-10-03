<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM espacios_fisicos";

$query = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espacios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style/style.css">

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>
    <main>
        <div id="contenido-mostrar-datos">
            <h1>Espacios Fisicos</h1>
            <table id="datos">
                <tr>
                    <th class="id">Id </th>
                    <th class="nombre-titulo">Capacidad</th>
                    <th class="nombre-titulo">Nombre</th>
                    <th class="titulo-ult">Tipo</th>
                    <th class="boton-titulo">Borrar</th>
                    <th class="boton-titulo">Editar</th>
                </tr>
                <?php while ($row = mysqli_fetch_array($query)): ?>
                    <tr class="mostrar-datos">
                        <th class="nombre"><?= $row['id_espacio'] ?></th>
                        <th class="nombre"><?= $row['capacidad'] ?></th>
                        <th class="nombre"><?= $row['nombre'] ?></th>
                        <th class="ultimo-dato"><?= $row['tipo'] ?></th>
                        <th class="boton-dato"><a href="#" class="boton-datos-eliminar botones-datos" data-id="<?= $row['id_espacio'] ?>">Eliminar</a></th>
                        <th class="boton-dato"><a class="boton-datos-editar botones-datos" data-id="<?= $row['id_espacio'] ?>" data-nombre="<?= $row['nombre'] ?>" data-capacidad="<?= $row['capacidad'] ?>" data-tipo="<?= $row['tipo'] ?>">Editar</a></th>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="overlay" id="overlay">
            <div class="confirmacion">
                <h2>¿Estás seguro?</h2>
                <p>Esta acción eliminará el registro de forma permanente.</p>
                <div class="botones_confirmar">
                    <button class="btn btn-confirmar" id="confirmar">Eliminar</button>
                    <button class="btn btn-cancelar" id="cancelar">Cancelar</button>
                </div>
            </div>
        </div>


        <div id="overlay-edit" class="overlay-edit">
            <form action="\backend\functions\Espacios\edit.php" method="POST">
                <h1>Registro de Espacios Fisicos</h1>
                <hr>
                <div class="div-labels">
                    <input class="input-register" type="hidden" name="id_espacio" id="id_edit">
                </div>
                <div class="editar-edit">
                    <label for="nombre" class="label">Nombre:</label>
                    <input class="class-datos-editar" type="text" name="nombre" id="name_edit" maxlength="20" minlength="3" required placeholder="Ingresa Nombre">
                    <label for="capacidad" class="label">Capacidad:</label>
                    <input class="class-datos-editar" type="text" name="capacidad" id="capacidad_edit" maxlength="20" minlength="3" required placeholder="Ingresa Capacidad">
                    <label for="tipo" class="label">Tipo:</label>
                    <select class="class-datos-editar" type="text" name="tipo" id="tipo_edit" maxlength="20" minlength="8" required placeholder="">
                        <option value=""></option>
                        <option value="aula">Aula</option>
                        <option value="salon">Salón</option>
                        <option value="laboratorio">Laboratorio</option>
                        <option value="SUM">SUM</option>
                    </select>
                </div>
                <div>
                    <input type="submit" value="Actualizar Infomacion" class="actualizar" id="actualizar"></input>
                    <input type="button" value="Cancelar" id="cancelarEdit"></input>
                </div>
            </form>
        </div>
    </main>

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/sideMenu.js"></script>
    <script src="/frontend/js/confirm-espacios.js"></script>
</body>

</html>