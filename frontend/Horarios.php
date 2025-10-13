<?php
include_once('../backend/db/conexion.php');

$connect = conectar_a_bd();
$sql = "SELECT * FROM horarios";
$query = mysqli_query($connect, $sql);

$sql2 = "SELECT * FROM cursos";
$query2 = mysqli_query($connect, $sql2);

$sql3 = "SELECT * FROM espacios_fisicos";
$query3 = mysqli_query($connect, $sql3);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style/style.css">

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>
    <main>
        <div id="contenido-mostrar-datos">
            <h1>Horarios</h1>
            <div class="filtros">
                <label for="horario-select">Seleccionar Horario:</label>
                <select id="select-horarios" name="horario-select">
                    <option value="1">Cursos</option>
                    <option value="2">Salones</option>
                </select>
                <div id="div-salones" style="display: none;">
                    <label for="Salones" id="select-salones">Seleccionar Salon:</label>
                    <select name="salones" id="salones-select">
                        <option value="1"></option>
                        <?php while ($row3 = mysqli_fetch_array($query3)): ?>
                            <option value="<?= $row3['id_espacio'] ?>"><?= $row3['nombre'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div id="div-curso" style="display: block;">
                    <label for="Cursos" id="select-cursos">Seleccionar Curso:</label>
                    <select name="Cursos" id="cursos-select">
                        <option value="1"></option>
                        <?php while ($row2 = mysqli_fetch_array($query2)): ?>
                            <option value="<?= $row2['id_curso'] ?>"><?= $row2['nombre'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="datos-header">
                <div class="datos-row">
                    <div class="horas-titulo">Horas</div>
                    <div class="dias">Lunes</div>
                    <div class="dias">Martes</div>
                    <div class="dias">Miércoles</div>
                    <div class="dias">Jueves</div>
                    <div class="dias">Viernes</div>
                </div>
            </div>

            <div class="datos-body">
                <?php while ($row = mysqli_fetch_array($query)): ?>
                    <div class="datos-row mostrar-datos">
                        <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                        <div class="dia-dato"><!-- Contenido Lunes --></div>
                        <div class="dia-dato"><!-- Contenido Martes --></div>
                        <div class="dia-dato"><!-- Contenido Miércoles --></div>
                        <div class="dia-dato"><!-- Contenido Jueves --></div>
                        <div class="dia-dato"><!-- Contenido Viernes --></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

    <!-- PARA HACER: ARREGLAR EL FOOTER QUE CON "ACTIVO" ANDA MAL -->
    <script type="module" src="/frontend/js/confirm-espacios.js"></script>
    <script type="module" src="/frontend/js/prueba.js"></script>
    <script src="/backend/functions/Horarios/select-horarios.js"></script>
</body>

</html>