<?php
include('PRUEBA_BASE_DE_DATOS/conexion.php');

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

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="register (temporal).css">
<link rel="stylesheet" href="asignaturas.css">

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
                        <th><a id="boton-datos-editar" class="botones-datos" href="/mostrar datos asignaturas/update-asignatura.php?id=<?= $row['id_asignatura'] ?>">Editar</a></th>
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
                <button class="btn btn-cancelar" id="cancelar">Cancelar</button>
                <button class="btn btn-confirmar" id="confirmar" href="/mostrar datos asignaturas/delete-asignatura.php?id=<?= $row['id_asignatura'] ?>" >Eliminar</button>
            </div>
        </div>
    </div>

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="sideMenu.js"></script>
    <script src="/Confirm-Delete.js"></script>
</body>

</html>