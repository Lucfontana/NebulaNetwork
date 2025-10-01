<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/perfil.css">
</head>

<body>
    <?php include 'nav.php'; ?>

    <main class="body-perfil">
        <div class="header-perfil">
            <div>
                <img class="icono-perfil" src="/frontend/img/Imagenes Luca/calendario.png" alt="Icono de usuario">
            </div>
            <div class="bienvenida-perfil">
                <?php if (isset($_SESSION['ci'])): ?>
                    <h2>¡Bienvenido, <?= $_SESSION['nombre_usuario']; ?>!</h2>
                <?php endif; ?>
            </div>
            <div class="editar-perfil">
                <button data-name="<?php $_SESSION['nombre_usuario']; ?>" id="editar-nombre">✏️<button>
            </div>
        </div>
        <div class="contenido-perfil">
            <div>
                <?php if (!isset($_SESSION['nivel_acceso'])): ?>
                    <h2>Rango Profesor</h2>
                <?php elseif ($_SESSION['nivel_acceso'] == "1"): ?>
                    <h2>Rango Adscripta</h2>
                <?php elseif ($_SESSION['nivel_acceso'] == "2"): ?>
                    <h2>Rango Secretaria</h2>
                <?php elseif ($_SESSION['nivel_acceso'] == "3"): ?>
                    <h2>Rango Administrador</h2>
                <?php endif; ?>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex incidunt iure deserunt quia quam facilis eius quisquam laudantium mollitia facere, a molestias ea iste neque odio tenetur non possimus veniam.</p>
            </div>
            <div>
                <h2>Contraseña</h2>
                <p>************</p>
                <button id="change" class="btn-cambiar-nombre">Cambiar contraseña</button>
            </div>
        </div>

        <div id="dialog-change-name" class="dialog-change">
            <form action="/backend/functions/edit-name-user.php" method="POST">
                <h1>Cambia tu Nombre</h1>
                <hr>
                <div class="change-nombre">
                    <input class="input-register" type="hidden" name="id_usuario" id="id_edit">
                </div>
                <div>
                    <label for="nombre" class="label">Nombre:</label>
                    <input class="nombre-editar" type="text" name="nombre" id="name_edit" maxlength="20" minlength="3" required placeholder="Ingresa tu nuevo nombre">
                </div>
                <div>
                    <?php if (!isset($_SESSION['nivel_acceso'])): ?>
                        <input type="submit" value="Actualizar Infomacion" class="actualizar" id="actualizar_profesor"></input>
                    <?php elseif ($_SESSION['nivel_acceso'] = "1"): ?>
                        <input type="submit" value="Actualizar Infomacion" class="actualizar" id="actualizar_adscripta"></input>
                    <?php elseif ($_SESSION['nivel_acceso'] = "2"): ?>
                        <input type="submit" value="Actualizar Infomacion" class="actualizar" id="actualizar_secretaria"></input>
                    <?php elseif ($_SESSION['nivel_acceso'] = "3"): ?>
                        <input type="submit" value="Actualizar Infomacion" class="actualizar" id="actualizar_administrador"></input>
                    <?php endif; ?>
                    <input type="button" value="Cancelar" id="cancelarEdit"></input>
                </div>
            </form>
        </div>

        <div id="dialog-change-passwd" class="dialog-change">
            <form action="/backend/functions/edit-name-user.php" method="POST">
                <h1>Cambia tu contraseña</h1>
                <div>
                    <label for="passwd" class="label">Ingrese su contraseña actual:</label>
                    <input class="nombre-editar" type="text" name="passwd" id="passwd" maxlength="20" minlength="3" required placeholder="Ingresa su contraseña actual">
                </div>
                <div>
                    <label for="newpasswd" class="label">Ingrese su nueva contraseña:</label>
                    <input class="nombre-editar" type="text" name="newpasswd" id="newpasswd" maxlength="20" minlength="3" required placeholder="Ingresa su nueva contraseña">
                </div>
            </form>
        </div>
    </main>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/sideMenu.js"></script>
    <script src="/frontend/js/changeName.js"></script>
</body>

</html>