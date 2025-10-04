<?php

include_once('../backend/db/conexion.php');
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
                    <select name="profesor_asignado" id="pertenece" type="text" class="input-register">
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($profesores_info)): ?>
                            <option value="<?= $row['ci_profesor']?>"><?= $row['nombre']?> <?= $row['apellido']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="div-labels">
                    <label for="recurso_prestado" class="label">Recurso a prestar:</label>
                    <select name="recurso_prestado" id="pertenece" type="text" class="input-register">
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
                    <th class="id">Id </th>
                    <th class="nombre-titulo">Espacio Fisico</th>
                    <th class="nombre-titulo">Nombre</th>
                    <th class="nombre-titulo">Estado</th>
                    <th class="titulo-ult">Tipo</th>
                    <th class="boton-titulo">Borrar</th>
                    <th class="boton-titulo">Editar</th>
                </tr>

                
                <?php 
                mysqli_data_seek($recursos_info, 0);
                while ($row = mysqli_fetch_array($recursos_info)): ?>
                    <tr class="mostrar-datos">
                        <th class="nombre"><?= $row['id_recurso'] ?></th>
                        <th class="nombre"><?= $row['id_espacio'] ?></th>
                        <th class="nombre"><?= $row['nombre'] ?></th>
                        <th class="nombre"><?= $row['estado'] ?></th>
                        <th class="ultimo-dato"><?= $row['tipo'] ?></th>
                        <th class="boton-dato"><a href="#" class="boton-datos-eliminar botones-datos" data-id="<?= $row['id_recurso'] ?>">Eliminar</a></th>
                        <th class="boton-dato"><a class="boton-datos-editar botones-datos" data-id="<?= $row['id_recurso'] ?>" data-espacio="<?= $row['id_espacio'] ?>" data-nombre="<?= $row['nombre'] ?>" data-estado="<?= $row['estado'] ?>" data-tipo="<?= $row['tipo'] ?>">Editar</a></th>
                    </tr>
                <?php endwhile; ?>
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
    <script src="js/sideMenu.js"></script>
    <script src="js/Register-Modal.js"></script>
    <script type="module" src="js/validaciones-registro.js" defer></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>