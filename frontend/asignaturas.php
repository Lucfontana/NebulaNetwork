<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM asignaturas";

$query = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style/style.css">

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>

    <div id="contenido-asignatura">
        <h1>Asignaturas</h1>
        <table id="datos">
            <tr>
                <th class="id">Id </th>
                <th class="nombre-titulo">Nombre</th>
                <th class="nombre-titulo"></th>
                <th class="nombre-titulo"></th>
            </tr>
            <?php while ($row = mysqli_fetch_array($query)): ?>
                <tbody>
                    <tr class="mostrar-datos">
                        <th><?= $row['id_asignatura'] ?></th>
                        <th class="nombre"><?= $row['nombre'] ?></th>
                        <th><a href="#" class="boton-datos-eliminar botones-datos" data-id="<?= $row['id_asignatura'] ?>">Eliminar</a></th>
                        <th><a class="boton-datos-editar botones-datos">Editar</a></th>
                    </tr>
                </tbody>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="overlay" id="overlay">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará el registro de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn btn-confirmar" id="confirmar" href="/backend/functions/mostrar datos asignaturas/delete-asignatura.php?id=<?= $row['id_asignatura'] ?>">Eliminar</button>
                <button class="btn btn-cancelar" id="cancelar">Cancelar</button>
            </div>
        </div>
    </div>

    <?php
    include_once('../backend/db/conexion.php');
    $connect = conectar_a_bd();
    $id = $_GET['id'];

    $sql = "SELECT * FROM asignaturas WHERE id_asignatura='$id'";
    $query = mysqli_query($connect, $sql);
    $row = mysqli_fetch_array($query)

    ?>

    <div id="overlay-edit" class="overlay-edit">
        <form action="/backend/functions/mostar datos asignaturas/edit-asignatura.php" method="POST">
            <h1>Registro de Asignaturas</h1>
            <hr>
            <div class="div-labels">
                <input class="input-register" type="hidden" name="id_asignatura" id="id" value="<?= $row['id_asignatura'] ?>">
            </div>
            <div>
                <label for="nombre" class="label">Nombre:</label>
                <input type="text" name="nombre" id="name" maxlength="20" minlength="3" required placeholder="Ingresa nombre" value="<?= $row['nombre'] ?>">
            </div>
            <div>
                <input type="submit" value="Actualizar Infomacion" id="actualizar" href="../backend/functions/mostrar datos asignaturas/update-asignatura.php?id=<?= $row['id_asignatura'] ?>"></input>
                <input type="button" value="Cancelar" id="cancelarEdit"></input>
            </div>
        </form>
    </div>

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/sideMenu.js"></script>
    <script src="js/Confirm-Delete.js"></script>
</body>

</html>