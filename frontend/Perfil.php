<?php
include_once 'functions.php';
session_start();
?>

<title><?= t("title_profile") ?></title>
<?php if (!isset($_SESSION['ci'])): ?>
    <?php include_once('error.php') ?>
<?php else: ?>
    <?php include './Complementos/nav.php'; ?>

    <body class="body-perfil">
        <div id="perfil-principal">
            <div class="main-perfil">
                <div class="header-banner"></div>
                <div class="header-perfil">
                    <div class="bienvenida-perfil">
                        <?php if (isset($_SESSION['ci'])): ?>
                            <h2><?= $_SESSION['nombre_usuario']; ?> <?= $_SESSION['apellido']; ?></h2>
                            <p><?= t("label_ci") ?> <?= $_SESSION['ci']; ?></p>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="contenido-perfil">
                    <div>
                        <?php if (!isset($_SESSION['nivel_acceso'])): ?>
                            <h2><?= t("role_teacher") ?></h2>
                            <p><?= t("desc_teacher_role") ?></p>
                        <?php elseif ($_SESSION['nivel_acceso'] == "1"): ?>
                            <h2><?= t("role_assistant") ?></h2>
                            <p><?= t("desc_assistant_role") ?></p>
                        <?php elseif ($_SESSION['nivel_acceso'] == "2"): ?>
                            <h2><?= t("role_secretary") ?></h2>
                            <p><?= t("desc_secretary_role") ?></p>
                        <?php elseif ($_SESSION['nivel_acceso'] == "3"): ?>
                            <h2><?= t("role_admin") ?></h2>
                            <p><?= t("desc_admin_role") ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <button id="change-passwd"><?= t("btn_change_password") ?></button>
                    </div>
                </div>

                <div id="dialog-change-passwd" class="overlay-edit">
                    <div class="popup">
                        <h1><?= t("title_change_password") ?></h1>
                        <p class="subtext"><?= t("desc_change_password") ?></p>
                        <form action="/backend/functions/edit-paswd-user.php" method="POST" id="comprobarcontraseña">
                            <div class="input-group">
                                <label for="passwd"><?= t("label_current_password") ?></label>
                                <div class="contenedor-password">
                                    <input type="password" name="passwd" id="passwd" maxlength="20" minlength="8" required placeholder="Ingresa su contraseña actual">
                                    <i class="far fa-eye fa-eye-slash togglePassword"></i>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="newpasswd"><?= t("label_new_password") ?></label>
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