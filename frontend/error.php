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
            <p>No tienes permiso para acceder a esta pagina, necesitas iniciar sesión.</p>
        <?php elseif(!isset($_SESSION['nivel_acceso'])):?>
            <p>No tienes el rango suficiente para acceder a esta pagina, debes ser un Superusuario.</p>
        <?php endif;?>

        <div class="botones-error">
            <a href="Login.php"><button id ="boton-inicio">Iniciar Sesion</button></a>
            <a href="index.php"><button id ="boton-inicio">Volver al inicio</button></a>
        </div>
    </div>
</body>
</html>