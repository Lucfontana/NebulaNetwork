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

<body class="body-perfil">
    <?php include 'nav.php'; ?>

    <main class="main-perfil">
        <div class="header-perfil">
            <div>
                <img class="icono-perfil" src="/frontend/img/Imagenes Luca/calendario.png" alt="Icono de usuario">
            </div>
            <div class="bienvenida-perfil">
                <?php if (isset($_SESSION['ci'])): ?>
                    <h2>¡Bienvenido, <?= $_SESSION['nombre_usuario']; ?>!</h2>
                <?php endif; ?>
            </div>

        </div>
        <div class="contenido-perfil">
            <div>
                <?php if (!isset($_SESSION['nivel_acceso'])): ?>
                    <h2>Rango Profesor</h2>
                    <p>Con el rango profesor vas a poder ver los horarios presentado en la aplicación. Eres un miembro escencial para la enseñanza del ITSP.</p>
                <?php elseif ($_SESSION['nivel_acceso'] == "1"): ?>
                    <h2>Rango Adscripta</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex incidunt iure deserunt quia quam facilis eius quisquam laudantium mollitia facere, a molestias ea iste neque odio tenetur non possimus veniam.</p>
                <?php elseif ($_SESSION['nivel_acceso'] == "2"): ?>
                    <h2>Rango Secretaria</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex incidunt iure deserunt quia quam facilis eius quisquam laudantium mollitia facere, a molestias ea iste neque odio tenetur non possimus veniam.</p>
                <?php elseif ($_SESSION['nivel_acceso'] == "3"): ?>
                    <h2>Rango Administrador</h2>
                    <p>El rango administrador es el rango mas alto en la aplicación, este rango es exclusivo de los desarrolladores principales de la pagina y estos tienen completo control de edición, eliminación y creación de datos en la base de datos de la aplicación.</p>
                <?php endif; ?>
            </div>
            <div>
                <h2>Contraseña</h2>
                <p>************</p>
                <button id="change-passwd">Cambiar contraseña</button>
            </div>
        </div>



        <div id="dialog-change-passwd" class="dialog-change">
            <form action="/backend/functions/edit-paswd-user.php" method="POST" id="comprobarcontraseña">
                <h1>Cambia tu contraseña</h1>
                <hr>
                <div class="datos-change-passwd">
                    <label for="passwd" class="label">Ingrese su contraseña actual</label>
                    <input class="dato-usuario-editar" type="password" name="passwd" id="passwd" maxlength="20" minlength="8" required placeholder="Ingresa su contraseña actual">
                    <label for="newpasswd" class="label">Ingrese su nueva contraseña</label>
                    <input class="dato-usuario-editar" type="password" name="newpasswd" id="newpasswd" maxlength="20" minlength="8" required placeholder="Ingresa su nueva contraseña">
                </div>
                <div>
                    <input type="submit" value="Actualizar Contraseña" class="actualizar" id="confirmarpasswd"></input>
                    <input type="button" value="Cancelar" id="cancelarEdit"></input>
                </div>
                    <p id="mensajeContraseña"></p>
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