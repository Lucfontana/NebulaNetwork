<?php
session_start();
?>

<title>Perfil</title>
<?php if (!isset($_SESSION['ci'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
<?php include 'nav.php'; ?>

<body class="body-perfil">
<div id="perfil-principal">
    <div class="main-perfil">
        <div class="header-banner"></div>
        <div class="header-perfil">
            <div id="icono-perfil-div">
                <img class="icono-perfil" src="/frontend/img/Imagenes Luca/calendario.png" alt="Icono de usuario">
                <button id="edit-perfil">✏️</button>
            </div>
            <div class="bienvenida-perfil">
                <?php if (isset($_SESSION['ci'])): ?>
                    <h2><?= $_SESSION['nombre_usuario']; ?> <?= $_SESSION['apellido']; ?></h2>
                    <p>CI: <?= $_SESSION['ci']; ?></p>
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
                <button id="change-passwd">Cambiar contraseña</button>
            </div>
        </div>

        <div id="dialog-change-passwd" class="overlay-edit">
            <div class="popup">
                <h1>Cambia tu contraseña</h1>
                <p class="subtext">Por seguridad, ingresa tu contraseña actual y una nueva.</p>
                <form action="/backend/functions/edit-paswd-user.php" method="POST" id="comprobarcontraseña">
                    <div class="input-group">
                        <label for="passwd">Ingresa tu contraseña actual</label>
                        <div class="contenedor-password">
                            <input type="password" name="passwd" id="passwd" maxlength="20" minlength="8" required placeholder="Ingresa su contraseña actual">
                            <i class="far fa-eye fa-eye-slash togglePassword"></i>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="newpasswd">Ingresa tu nueva contraseña</label>
                        <div class="contenedor-password">
                            <input type="password" name="newpasswd" id="newpasswd" maxlength="20" minlength="8" required placeholder="Ingresa su nueva contraseña">
                            <i class="far fa-eye fa-eye-slash togglePassword"></i>
                        </div>
                    </div>
                    <div class="buttons-modal">
                        <input type="submit" value="Actualizar Contraseña" id="confirmarpasswd" class="btn-primary"></input>
                        <input type="button" value="Cancelar" id="cancelarEdit" class="btn-secondary"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="/frontend/js/changeName.js"></script>
    <script src="./js/togglepasswd.js"></script>
</body>
<?php endif; ?>
