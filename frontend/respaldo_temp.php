<?php

include_once('../backend/db/conexion.php');
$con = conectar_a_bd();
////////////////////////////////
//Query de profesores
$query_profesores = "SELECT * FROM profesores";
$stmt = $con->prepare($query_profesores);
$stmt->execute();
$profesores_info = $stmt->get_result();

//////////////////////////////////
//Query de recursos
$query_recursos = "SELECT * FROM recursos WHERE estado != 'uso'";
$stmt = $con->prepare($query_recursos);
$stmt->execute();
$recursos_info = $stmt->get_result();

//////////////////////////////////
// Consulta SQL para obtener la información de préstamos
$query_unida = "SELECT 
-- SAR es el apodo de la tabla su_administra_recursos, 
-- le dice que traiga id_solicita, hora presta y vuelta de esa tabla
    sar.id_solicita, sar.hora_presta, sar.hora_vuelta, 

-- p es el apodo de profesores.
-- trae datos con apodos (por ejemplo, nombre 
-- lo trae como nombre_profesor)
    p.nombre AS nombre_profesor, p.apellido AS apellido_profesor, p.ci_profesor, 
-- r = apodo de tabla RECURSOS
    r.nombre AS nombre_recurso, r.id_recurso, r.estado AS estado_recurso,

-- su = apodo de tabla SUPERUSUARIO
    su.nombre AS nombre_su, su.apellido AS apellido_su, su.id_superusuario

-- Junta tablas que tienen datos en comun, diciendo que el origen es su_administra_usuarios y tiene apodo de 'sar'
-- Nosotros esto lo haciamos con WHERE el anio pasado, pero queda mas legible con INNER JOIN
    FROM su_administra_recursos sar
    INNER JOIN profesor_solicita_recurso psr ON sar.id_solicita = psr.id_solicita
    INNER JOIN profesores p ON psr.ci_profesor = p.ci_profesor
    INNER JOIN recursos r ON psr.id_recurso = r.id_recurso
    INNER JOIN superUsuario su ON sar.id_superusuario = su.id_superusuario

-- Se ordena como descendiente para que los mas nuevos aparezcan primero
    ORDER BY sar.hora_presta DESC";

    //Se ejecuta la query y se trae el resultado
    $stmt = $con->prepare($query_unida);
    $stmt->execute();
    $prestamos_info = $stmt->get_result();

session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestar recursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="style/style.css">

<?php if (isset($_SESSION['nivel_acceso'])):?>

<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>

    <main>
            <div id="register-content">
                <div class="article-register">
                    <div>
                        <h1> Prestar recursos</h1>
                    </div>
                    <button type="button" id="Adscriptas-boton" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                        Prestar
                    </button>
                </div>

            </div>
        </div>


        <!--    Inicio de Ventanas Emergentes    -->

        <div id="div-dialogs">

        <dialog>
            <form id="form-registro" class="registro-div superusuarios-form"
                action="../backend/functions/Recursos/prestar-recursos/prestar_api.php" method="POST">
                <h1>Prestar Recursos</h1>
                <hr>

                <div class="div-labels">
                    <label for="profesor_asignado" class="label">Profesor a prestar:</label>
                    <select name="profesor_asignado" id="pertenece" type="text" class="input-register" required>
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($profesores_info)): ?>
                            <option value="<?= $row['ci_profesor']?>"><?= $row['nombre']?> <?= $row['apellido']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="recurso_prestado" class="label">Recurso a prestar:</label>
                    <select name="recurso_prestado" id="pertenece" type="text" class="input-register" required>
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($recursos_info)): ?>
                            <option value="<?= $row['id_recurso']?>"><?= $row['nombre']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>                
                    
                    <div class="div-botones-register">
                        <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                            name="prestarRecurso"></input>
            </form>
            <button class="btn-Cerrar" type="button">Cerrar</button>
            </div>
        </dialog>
    </main>
    <main>

            <div id="contenido-mostrar-datos">
            <h1>Recursos</h1>

<table id="datos">
    <tr>
        <th class="id">Id Solicitud</th>
        <th class="nombre-titulo">Persona que Pidió</th>
        <th class="nombre-titulo">Recurso Prestado</th>
        <th class="nombre-titulo">Administrador que Prestó</th>
        <th class="nombre-titulo">Hora Prestado</th>
        <th class="titulo-ult">Hora Devolución</th>
        <th class="boton-titulo">Estado</th>
    </tr>

    <?php 
    if (mysqli_num_rows($prestamos_info) > 0) {
        while ($row = mysqli_fetch_array($prestamos_info)): 
    ?>
        <tr class="mostrar-datos">
            <th class="nombre"><?= $row['id_solicita'] ?></th>
            <th class="nombre">
                <?= $row['nombre_profesor'] . ' ' . $row['apellido_profesor'] ?>
                <br><small>(CI: <?= $row['ci_profesor'] ?>)</small>
            </th>
            <th class="nombre">
                <?= $row['nombre_recurso'] ?>
            </th>
            <th class="nombre">
                <?= $row['nombre_su'] . ' ' . $row['apellido_su']?>
            </th>
            <th class="nombre">
                <?= date('d/m/Y H:i:s', strtotime(($row['hora_presta']))) ?>
            </th>
            <th class="ultimo-dato">
                <?= $row['hora_vuelta'] ? date('d/m/Y H:i', strtotime($row['hora_vuelta'])) : 'Pendiente' ?>
            </th>
            <th class="boton-dato">
                <?php if ($row['hora_vuelta']): ?>
                    <span style="color: green; margin-left: 20px;"> Devuelto</span>
                <?php else: ?>
                    <a class="boton-datos-editar botones-datos btn-devolver" 
                       data-id="<?= $row['id_solicita'] ?>"
                       data-recurso="<?= $row['id_recurso'] ?>"
                       data-nombre-recurso="<?= htmlspecialchars($row['nombre_recurso']) ?>">
                       Devolver
                    </a>
                <?php endif; ?>
            </th>
        </tr>
    <?php 
        endwhile;
    } else {
        echo '<tr><td colspan="7" style="text-align:center;">No hay préstamos registrados</td></tr>';
    }
    ?>
</table>
        </div>
    </main>

    <!--    Cierre de Ventanas Emergentes    -->

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>
    <?php elseif (isset($_SESSION['ci'])):?>
        <p>HOLA SOY UN PROFESOR!!! PAGINA EN DESARROLLO</p>
    <?php elseif (!isset($_SESSION['ci'])):?>
        <?php include_once('error.php')?>
    <?php endif;?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>

    <script src="../../backend/functions/Recursos/prestar-recursos/procesar_devolucion.js"></script>
    <script src="js/Register-Modal.js"></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>