<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM profesores";

$query = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style/style.css">

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>
    <main>
        <div id="contenido-mostrar-datos">
            <h1>Profesores</h1>
            <table id="datos">
                <tr>
                    <th class="id">CI </th>
                    <th class="nombre-titulo">Nombre</th>
                    <th class="nombre-titulo">Apellido</th>
                    <th class="nombre-titulo">Email</th>
                    <th class="nombre-titulo">F.Nac</th>
                    <th class="titulo-ult">Direccion</th>
                    <th class="boton-titulo">Eliminar</th>
                    <th class="boton-titulo">Editar</th>
                </tr>
                <?php while ($row = mysqli_fetch_array($query)): ?>
                    <tr class="mostrar-datos">
                        <th class="nombre"><?= $row['ci_profesor'] ?></th>
                        <th class="nombre"><?= $row['nombre'] ?></th>
                        <th class="nombre"><?= $row['apellido'] ?></th>
                        <th class="nombre"><?= $row['email'] ?></th>
                        <th class="nombre"><?= $row['fecha_nac'] ?></th>
                        <th class="ultimo-dato"><?= $row['direccion'] ?></th>
                        <th class="boton-dato"><a href="#" class="boton-datos-eliminar botones-datos" data-id="<?= $row['ci_profesor'] ?>">Eliminar</a></th>
                        <th class="boton-dato"><a class="boton-datos-editar botones-datos" data-id="<?= $row['ci_profesor'] ?>" data-nombre="<?= $row['nombre'] ?>" data-apellido="<?= $row['apellido'] ?>" data-email="<?= $row['email'] ?>" data-fnac="<?= $row['fecha_nac'] ?>" data-direccion="<?= $row['direccion'] ?>">Editar</a></th>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="overlay" id="overlay">
            <div class="confirmacion">
                <h2>¿Estás seguro?</h2>
                <p>Esta acción eliminará el registro de forma permanente.</p>
                <div class="botones_confirmar">
                    <button class="btn btn-confirmar" id="confirmar" href="backend/functions/Profesores/delete.php?id=<?= $row['ci_profesor'] ?>">Eliminar</button>
                    <button class="btn btn-cancelar" id="cancelar">Cancelar</button>
                </div>
            </div>
        </div>


        <div id="overlay-edit" class="overlay-edit">
            <div class="popup">
                <h1>Registro de Profesores</h1>
                <form action="\backend\functions\Profesores\edit.php" method="POST" id="form-update">
                    <div class="div-labels">
                        <input class="input-register" type="hidden" name="ci_profesor" id="id_edit">
                    </div>

                    <div class="input-group">
                        <label for="nombre">Nombre:</label>
                        <div>
                            <input class="class-datos-editar" type="text" name="nombre" id="name_edit" maxlength="20" minlength="3" required placeholder="Ingresa nombre">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="apellido">Apellido:</label>
                        <div>
                            <input class="class-datos-editar" type="text" name="apellido" id="apellido_edit" maxlength="20" minlength="3" required placeholder="Ingresa apellido">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="email">Email:</label>
                        <div>
                            <input class="class-datos-editar" type="email" name="email" id="email_edit" maxlength="40" minlength="5" required placeholder="Ingresa email">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="fnac">Fecha de Nacimiento:</label>
                        <div>
                            <input class="class-datos-editar" type="date" name="fnac" id="fnac_edit" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="direccion">Dirección:</label>
                        <div>
                            <input class="class-datos-editar" type="text" name="direccion" id="direccion_edit" maxlength="50" minlength="3" required placeholder="Ingresa dirección">
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

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/sideMenu.js"></script>
    <script type="module" src="/frontend/js/confirm-profesores.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
</body>

</html>