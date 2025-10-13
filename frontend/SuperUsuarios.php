<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM superusuario";

$query = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperUsuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style/style.css">

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>
    <main>
        <div id="contenido-mostrar-datos">
            <h1>SuperUsuario</h1>

            <div class="datos-grid superusuario-grid">
                <!-- Cabecera -->
                <div class="grid-header superusuario-header">
                    <div class="grid-cell id">CI</div>
                    <div class="grid-cell nombre-titulo">Nombre</div>
                    <div class="grid-cell nombre-titulo">Apellido</div>
                    <div class="grid-cell nombre-titulo">Nivel de Acceso</div>
                    <div class="grid-cell nombre-titulo">Email</div>
                    <div class="grid-cell boton-titulo">Eliminar</div>
                    <div class="grid-cell boton-titulo">Editar</div>
                </div>

                <!-- Filas de datos -->
                <?php while ($row = mysqli_fetch_array($query)): ?>
                    <div class="grid-row superusuario-row mostrar-datos">
                        <div class="grid-cell"><?= $row['id_superusuario'] ?></div>
                        <div class="grid-cell"><?= $row['nombre'] ?></div>
                        <div class="grid-cell"><?= $row['apellido'] ?></div>
                        <div class="grid-cell"><?= $row['nivel_acceso'] ?></div>
                        <div class="grid-cell"><?= $row['email_superusuario'] ?></div>
                        <div class="grid-cell">
                            <a href="#"
                                class="boton-datos-eliminar botones-datos"
                                data-id="<?= $row['id_superusuario'] ?>">
                                Eliminar
                            </a>
                        </div>
                        <div class="grid-cell">
                            <a class="boton-datos-editar botones-datos"
                                data-id="<?= $row['id_superusuario'] ?>"
                                data-nombre="<?= $row['nombre'] ?>"
                                data-apellido="<?= $row['apellido'] ?>"
                                data-nivel="<?= $row['nivel_acceso'] ?>"
                                data-email="<?= $row['email_superusuario'] ?>">
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
                    <button class="btn btn-confirmar" id="confirmar" href="backend/functions/Cursos/delete.php?id=<?= $row['id_superusuario'] ?>">Eliminar</button>
                    <button class="btn btn-cancelar" id="cancelar">Cancelar</button>
                </div>
            </div>
        </div>


        <div id="overlay-edit" class="overlay-edit">
            <div class="popup">
                <h1>Modificación de Super Usuario</h1>
                <form action="\backend\functions\SuperUsuarios\edit.php" method="POST" id="form-update">
                    <div class="div-labels">
                        <input class="input-register" type="hidden" name="id_superusuario" id="id_edit">
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
                        <label for="nivelacceso">Nivel de Acceso:</label>
                        <div>
                            <select class="class-datos-editar" name="nivelacceso" id="nivel_edit" required>
                                <option value=""></option>
                                <option value="1">1 - Adscripta</option>
                                <option value="2">2 - Secretaría</option>
                                <option value="3">3 - Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="email"></label>
                        <div>
                            <input type="email" class="class-datos-editar" name="email" id="email_edit" required>
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
    <script type="module" src="/frontend/js/confirm-superusuario.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
</body>

</html>