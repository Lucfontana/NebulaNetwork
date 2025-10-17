<?php

//session_start();

include_once 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <?php if (!isset($_SESSION['ci'])): ?>
        <div id="session-status" style="display: none;">1</div>
    <?php elseif (isset($_SESSION['ci'])): ?>
        <div id="session-status" style="display: none;">2</div>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg bg-body-tertiary navStyle" id="nav">
        <div class="container-fluid navStyle">

            <a class="navbar-brand" href="index.php"><img id="logo" src="img/logo.png">ITSP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" id="desplegar"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= t("nav_info") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= t("nav_courses") ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <aside id="aside">
        <ul class="link-aside-images" id="link-aside-images">

        <!-- Login / Perfil -->
        <li class="aside-item">
        <?php if (!isset($_SESSION['ci'])): ?>
            <a href="Login.php" data-tooltip="Loginperson-circle_white_no_bg">
            <img src="img/Iconos sidebar/person-circle.svg" alt="Login" width="25" height="25" class="icono">
            </a>
        <?php else: ?>
            <div class="settings-icon" data-tooltip="Perfil">
            <img src="img/Iconos sidebar/person-circle.svg" alt="Configuraciones" width="25" height="25" class="icono">
            <div class="settings-menu">
                <a href="/frontend/Perfil.php"><?= t("Perfil") ?></a>
                <a id="logout" href="#logout"><?= t("Cerrar Sesión") ?></a>
            </div>
            </div>
        <?php endif; ?>
        </li>

        <!-- Horarios -->
        <li class="aside-item" data-tooltip="<?= t('aside_schedule') ?>">
        <a href="./Horarios.php">
            <img src="img/Iconos sidebar/calendar4-week.svg" alt="Calendario" width="25" height="25" class="icono">
        </a>
        </li>

        <!-- Idioma -->
        <li class="aside-item lang-icon" data-tooltip="<?= t('aside_lang') ?>">
        <a href="?lang=<?= $lang === 'es' ? 'en' : 'es' ?>">
            <img src="img/Iconos sidebar/translate.svg" alt="Idioma" width="25" height="25" class="icono">
        </a>
        </li>

        <!-- Modo oscuro -->
        <li class="aside-item" id="darkmode-icon" data-tooltip="<?= t('aside_darkmode') ?>">
        <a href="#modo-oscuro">
            <img src="img/Iconos sidebar/moon.svg" alt="Modo oscuro" width="25" height="25" class="icono">
        </a>
        </li>

        </ul>
    </aside>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/confirm-logout.js"></script>
    <!-- <script src="js/darkmode.js"></script> -->

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/login-expirate.js"></script>
    <script type="module" src="js/swalerts.js"></script>
    <script type="module" src="js/prueba.js"></script>

</body>

</html>