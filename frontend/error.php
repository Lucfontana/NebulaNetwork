<?php
include 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Error</title>
</head>
<body class="error-body">
    <div class="error">
        <h1>Error...</h1>
        <?php if (!isset($_SESSION['ci'])):?>
            <p><?= t("error_not_logged_in") ?></p>
        <?php elseif(!isset($_SESSION['nivel_acceso'])):?>
            <p><?= t("error_insufficient_access") ?></p>
        <?php endif;?>

        <div class="botones-error">
            <a href="Login.php"><button id ="boton-inicio"><?= t("btn_login") ?></button></a>
            <a href="index.php"><button id ="boton-inicio"><?= t("btn_home") ?></button></a>
        </div>
    </div>
</body>
</html>