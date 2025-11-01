<?php
include_once 'functions.php';
session_start();

?>

<title>Index</title>
<?php include './Complementos/nav.php'; ?>

<body>
 <div class="hdrBackground" id="hdrBackground">
    <!-- Texto de Bienvenidos -->
    <div class="bienvenida-texto">
        <?php if (!isset($_SESSION['ci'])): ?>
        <h2><?= t("welcome") ?></h2> <!-- Cargamos el texto desde lang.json --> 
        <?php elseif (isset($_SESSION['ci'])): ?>
         <h2><?= t("welcome_user", $_SESSION['nombre_usuario']); ?></h2>
        <?php endif; ?>
        <p><?= t("description") ?></p>
    </div>

    <!-- Botones debajo -->
    <div class="bienvenida-botones">
        <a href="Mostrar_informacion.php"><button class="btn-bienvenida" id ="btn-bienvenida"><?= t("btn_info") ?></button></a>
        <a href="respaldo_temp.php"><button class="btn-bienvenida" id ="btn-bienvenida"><?= t("btn_resources") ?></button></a>
        <a href="Horarios.php"><button class="btn-bienvenida" id ="btn-bienvenida"><?= t("btn_teachers") ?></button></a>
    </div>
 </div>

    <!-- <main class="contenido" id="contenido">

    </main> -->
    <?php include_once("./Complementos/footer.php") ?>
    <script src="js/darkmode.js"></script>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->


</body>