<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="flex">
        <form id="form" method="POST" action="../backend/login/login_usrs.php">
            <h2 id="titulo-login">Inicio de Sesión</h2>
            <div id="centro-form">
                <label for="CI" class="label">Cedula de Identidad:</label>
                <input class="input-form" type="text" name="CI" id="CI" maxlength="8" minlength="8" pattern="\d{8}"
                    required placeholder="Ingresa sin puntos ni guiones">
                <label for="CI" class="label">Contraseña:</label>
                <div class="contenedor-password">
                    <input class="input-form" type="password" name="contrasena" id="contrasena" maxlength="20"
                        minlength="8" required placeholder="Ingrese Contraseña">
                    <i class="far fa-eye fa-eye-slash togglePassword"></i>
                </div>
                <div id="botones-login">
                    <button type="submit" name="loginUsuario" class="btn-login">Iniciar Sesión</button>
                </div>

                <a href="index.php" class="flecha-volver" title="Volver al inicio"> &#8592;</a>

            </div>

        </form>
        <div class="fondo-login">
            <img id="logo-fondo" src="img/fondo-login.jpg" alt="">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script type="module" src="js/login.js"></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/togglepasswd.js"></script>
</body>

</html>