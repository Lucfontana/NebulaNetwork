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

    <?php if (!isset($_SESSION['nivel_acceso'])):?>
        <?php include_once('error.php')?>
    <?php else:?>

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>

    <main>
    <div id="contenido-mostrar-datos">
        <h1>Asignaturas</h1>
        <table id="datos">
            <tr>
                <th class="id">Id </th>
                <th class="titulo-ult">Nombre</th>
                <th class="boton-titulo">Borrar</th>
                <th class="boton-titulo">Editar</th>
            </tr>
            <?php while ($row = mysqli_fetch_array($query)): ?>
                    <tr class="mostrar-datos">
                        <th class="nombre"><?= $row['id_asignatura'] ?></th>
                        <th class="ultimo-dato"><?= $row['nombre'] ?></th>
                        <th class="boton-dato"><a href="#" class="boton-datos-eliminar botones-datos" data-id="<?= $row['id_asignatura'] ?>">Eliminar</a></th>
                        <th class="boton-dato"><a class="boton-datos-editar botones-datos" data-id="<?= $row['id_asignatura'] ?>" data-nombre="<?= $row['nombre']?>">Editar</a></th>
                    </tr>
            <?php endwhile; ?>
        </table>
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
        <form action="\backend\functions\asignaturas\edit.php" method="POST">
            <h1>Registro de Asignaturas</h1>
            <hr>
            <div class="div-labels">
                <input class="input-register" type="hidden" name="id_asignatura" id="id_edit">
            </div>
            <div>
                <label for="nombre" class="label">Nombre:</label>
                <input class="class-datos-editar" type="text" name="nombre" id="name_edit" maxlength="20" minlength="3" required placeholder="Ingresa nombre">
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

    <?php endif;?>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/sideMenu.js"></script>
    <script src="/frontend/js/confirm-asignatura.js"></script>
</body>

</html>