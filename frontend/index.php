<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="style/style.css">

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>

 <div class="hdrBackground" id="hdrBackground">
    <!-- Texto de Bienvenidos -->
    <div class="bienvenida-texto">
        <?php if (!isset($_SESSION['ci'])): ?>
        <h2>¡Bienvenidos!</h2>
        <?php elseif (isset($_SESSION['ci'])): ?>
        <h2>¡Bienvenido, <?= $_SESSION['nombre_usuario'];?>!</h2>
        <?php endif; ?>
        <p>
            Bienvenido a la herramienta de gestión de institutos, donde podrás administrar la solicitud de recursos 
            al igual que administrar horarios de profesores.
        </p>
    </div>

    <!-- PARA HACER: Los botones no son responsive D: -->
    <!-- Botones debajo -->
    <div class="bienvenida-botones">
        <a href="register.php"><button class="btn-bienvenida">Ingresar información</button></a>
        <button class="btn-bienvenida">Cursos</button>
        <button class="btn-bienvenida">Docentes</button>
    </div>
 </div>


    <!-- <main class="contenido" id="contenido">

    </main> -->
     <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="js/sideMenu.js"></script>


</body>
   

</html>