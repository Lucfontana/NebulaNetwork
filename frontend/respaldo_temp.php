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

if (!isset($_SESSION['acceso']) && isset($_SESSION['ci'])) {
    $ci_profesor = (int)$_SESSION['ci'];
}

$query = "SELECT 
    r.nombre as nombre_recurso,
    sar.hora_presta,
    sar.hora_vuelta
    FROM profesor_solicita_recurso psr
    INNER JOIN recursos r ON psr.id_recurso = r.id_recurso
    INNER JOIN su_administra_recursos sar ON psr.id_solicita = sar.id_solicita
    WHERE psr.ci_profesor = ? AND sar.hora_vuelta is null
    ORDER BY sar.hora_presta DESC";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $ci_profesor);
$stmt->execute();
$prestamos_info2 = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<title>Prestar Recursos</title>
<?php include 'nav.php'; ?>
<link rel="stylesheet" href="style/style.css">

<?php if (isset($_SESSION['nivel_acceso'])): ?>

    <body id="body-register">
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

                <div class="overlay">
                    <div class="dialogs">
                        <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png" alt=""></button>
                        <form id="form-registro" class="registro-div prestar-form">
                            <h1>Prestar Recursos</h1>
                            <hr>

                            <div class="div-labels">
                                <label for="profesor_asignado" class="label">Profesor a prestar:</label>
                                <select name="profesor_asignado" id="profesor_asignado" type="text" class="input-register" required>
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($profesores_info)): ?>
                                        <option value="<?= $row['ci_profesor'] ?>"><?= $row['nombre'] ?> <?= $row['apellido'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-labels">
                                <label for="recurso_prestado" class="label">Recurso a prestar:</label>
                                <select name="recurso_prestado" id="recurso_prestado" type="text" class="input-register" required>
                                    <option value=""></option>
                                    <?php while ($row = mysqli_fetch_array($recursos_info)): ?>
                                        <option value="<?= $row['id_recurso'] ?>"><?= $row['nombre'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="div-botones-register">
                                <input id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar"
                                    name="prestarRecurso"></input>
                        </form>
                    </div>
                </div>
        </main>
        <main>

            <div id="contenido-mostrar-datos">
                <h1>Préstamos de Recursos</h1>

                <div class="datos-grid prestamos-grid">
                    <!-- Cabecera -->
                    <div class="grid-header prestamos-header">
                        <div class="grid-cell id">ID</div>
                        <div class="grid-cell nombre-titulo">Persona que Pidió</div>
                        <div class="grid-cell nombre-titulo">Recurso Prestado</div>
                        <div class="grid-cell nombre-titulo">Administrador que Prestó</div>
                        <div class="grid-cell nombre-titulo">Hora Prestado</div>
                        <div class="grid-cell titulo-ult">Hora Devolución</div>
                        <div class="grid-cell boton-titulo">Estado</div>
                    </div>

                    <!-- Filas de datos -->
                    <?php
                    if (mysqli_num_rows($prestamos_info) > 0) {
                        while ($row = mysqli_fetch_array($prestamos_info)):
                    ?>
                            <div class="grid-row prestamos-row mostrar-datos">
                                <div class="grid-cell"><?= $row['id_solicita'] ?></div>
                                <div class="grid-cell">
                                    <?= $row['nombre_profesor'] . ' ' . $row['apellido_profesor']?>
                                    <br><small>(CI: <?= $row['ci_profesor'] ?>)</small>
                                </div>
                                <div class="grid-cell"><?= $row['nombre_recurso'] ?></div>
                                <div class="grid-cell"><?= $row['nombre_su'] . ' ' . $row['apellido_su'] ?></div>
                                <div class="grid-cell"><?= date('d/m/Y H:i:s', strtotime($row['hora_presta'])) ?></div>
                                <div class="grid-cell"><?= $row['hora_vuelta'] ? date('d/m/Y H:i', strtotime($row['hora_vuelta'])) : 'Pendiente' ?></div>
                                <div class="grid-cell">
                                    <?php if ($row['hora_vuelta']): ?>
                                        <span style="color: green; margin-left: 20px;">Devuelto</span>
                                    <?php else: ?>
                                        <a class="boton-datos-editar botones-datos btn-devolver"
                                            data-id="<?= $row['id_solicita'] ?>"
                                            data-recurso="<?= $row['id_recurso'] ?>"
                                            data-nombre-recurso="<?= htmlspecialchars($row['nombre_recurso']) ?>">
                                            Devolver
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    } else {
                        echo '<div class="grid-row" style="text-align:center;"><div class="grid-cell" style="grid-column: 1 / -1;">No hay préstamos registrados</div></div>';
                    }
                    ?>
                </div>
            </div>
        </main>

        <!--    Cierre de Ventanas Emergentes    -->

        <footer id="footer" class="footer">
            <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
        </footer>
    <?php elseif (isset($_SESSION['ci'])): ?>
        <?php include_once('nav.php') ?>
        <main>
            <div id="contenido-mostrar-datos">
                <h1>Préstamos de Recursos</h1>

                <div class="datos-grid prestamos-grid">
                    <!-- Cabecera -->
                    <div class="grid-header prestamos-header">
                        <div class="grid-cell nombre-titulo">Recurso</div>
                        <div class="grid-cell nombre-titulo">Hora Prestado</div>
                        <div class="grid-cell titulo-ult">Hora Devolución</div>
                    </div>

                    <!-- Filas de datos -->
                    <?php
                    if (mysqli_num_rows($prestamos_info2) > 0) {
                        while ($row = mysqli_fetch_array($prestamos_info2)):
                    ?>
                            <div class="grid-row prestamos-row mostrar-datos">
                                <div class="grid-cell"><?= $row['nombre_recurso'] ?></div>
                                <div class="grid-cell">
                                    <?= date('d/m/Y H:i:s', strtotime($row['hora_presta'])) ?>
                                </div>
                                <div class="grid-cell">
                                    <?php if ($row['hora_vuelta']): ?>
                                        <?= date('d/m/Y H:i:s', strtotime($row['hora_vuelta'])) ?>
                                    <?php else: ?>
                                        <span style="color: orange;">Sin devolver</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    } else {
                        echo '<div class="grid-row" style="text-align:center;"><div class="grid-cell" style="grid-column: 1 / -1;">No tienes préstamos registrados</div></div>';
                    }
                    ?>
                </div>
            </div>
        </main>
        <footer id="footer" class="footer">
            <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
        </footer>

    <?php elseif (!isset($_SESSION['ci'])): ?>
        <?php include_once('error.php') ?>
    <?php endif; ?>

    <!-- Bootstrap -->
    <script type="module" src="../../backend/functions/Recursos/prestar-recursos/prestar.js"></script>
    <script type="module" src="../../backend/functions/Recursos/prestar-recursos/procesar_devolucion.js"></script>
    <script src="js/Register-Modal.js"></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

</html>