<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="../style.css">

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary navStyle">
        <div class="container-fluid navStyle">
            <button class="sideButton" id="sideButton">
                <img src="/img/box-arrow-right-white.svg" alt="menu" class="sideMenu">                
            </button>
            
            <a class="navbar-brand" href="index.php"><img id="logo" src="/img/logo.png">ITSP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">INFORMACIÓN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">CURSOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">DOCENTES</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <aside id="aside">
        <ul class="link-aside-text" id="link-aside-text">
            <li><a class="link-aside" href="Login.php">Mi cuenta</a></li>

            <li><a class="link-aside">Horario</a></li>

            <li><a class="link-aside">Galeria</a></li>

            <li><a class="link-aside">Eventos</a></li>

            <li><a class="link-aside">Notificaciones</a></li>
        </ul>

        <ul class="link-aside-images" id="link-aside-images">
            <a href="Login.php"><li><img src="/img/person-circle_white_no_bg.png" alt="" height="25px" width="25px"></li></a>
            <a href=""><li><img src="/img/Iconos sidebar/calendario.png" alt="" height="25px" width="25px"></li></a>
            <a href=""><li><img src="/img/Iconos sidebar/galeria.png" alt="" height="25px" width="25px"></li></a>
            <a href=""><li><img src="/img/Iconos sidebar/Eventos.png" alt="" height="25px" width="25px"></li></a>
            <a href=""><li><img src="/img/Iconos sidebar/notificacion.png" alt="" height="25px" width="25px"></li></a>         
        </ul>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="sideMenu.js"></script>

</body>
   

</html>