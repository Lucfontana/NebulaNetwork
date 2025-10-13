<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
     <div class="flex">
        <form id="form" method="POST" action="../backend/login/login_usrs.php">
            <h2 id="titulo-login">Inicio de Sesión</h2>
        <div id="centro-form">
            <label for="CI" class="label">Cedula de Identidad:</label>
                <input class="input-form" type="text"  name="CI" id="CI" maxlength="8" minlength="8" pattern="\d{8}" required placeholder="Ingresa sin puntos ni guiones">
            <label for="CI" class="label">Contraseña:</label>
                <input class="input-form" type="password" name="contrasena" id="contrasena" maxlength="20" minlength="8" required placeholder="Ingrese Contraseña">
            
        <div id="botones-login">
            <button type="submit" name="loginUsuario" class="btn-login">Iniciar Sesión</button>
        </div>


            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>

     <a href="index.php" class="flecha-volver" title="Volver al inicio"> &#8592;</a>

    <div id="alert" class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
            <use xlink:href="#exclamation-triangle-fill" />
        </svg>
        <div id="alert.text">
        </div>
    </div>
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
</body>
</html>