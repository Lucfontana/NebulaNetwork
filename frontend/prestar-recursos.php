<?php

include_once('../backend/db/conexion.php');

$con = conectar_a_bd();
$sql = "SELECT * FROM espacios_fisicos";
$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

////////////////////////////////
//Query de profesores
$query_profesores = "SELECT * FROM profesores";
$stmt = $con->prepare($query_profesores);
$stmt->execute();
$profesores_info = $stmt->get_result();

////////////////////////////////
//Query de recursos
$query_recursos = "SELECT * FROM recursos";
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
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="style/style.css">

    <?php if(isset($_SESSION['nivel_acceso'])):?>
<body>
    <!-- trae las barras de navegacion (sidebar y superior) -->
    <?php include 'nav.php'; ?>
<div>
    <main id="contenido" class="contenido-centrado">

        <div id="register-content">
            <div class="article-register">
                <div>
                    <h1>Prestar recursos</h1>
                </div>
                <button type="button" id="Profesores-boton" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Abrir Registro
                </button>
            </div>

            <div class="article-register">
                <div>
                    <h1> Registro de Recursos</h1>
                </div>
                <button type="button" id="Recursos-boton" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Abrir Registro
                </button>
            </div>
        </div>
    </div>

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
                <?php while ($row = mysqli_fetch_array($recursos_info)): ?>
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
    
<!--    Inicio de Ventanas Emergentes    -->

<div id="div-dialogs">
<dialog>
    <form id="form-registro" class="registro-div profesores-form" action="../backend/functions/profesores_func.php" method="POST"> 
    <h1>Prestar recursos</h1><hr>
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
            <label for="profesor_asignado" class="label">Recurso a prestar:</label>
            <select name="profesor_asignado" id="pertenece" type="text" class="input-register">
                <option value=""></option>
                <?php while ($row = mysqli_fetch_array($recursos_info)): ?>
                    <option value="<?= $row['id_recurso']?>"><?= $row['nombre']?></option>
                <?php endwhile; ?>
            </select>
        </div>
    <div class="div-botones-register">
    <input  id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar" name="registroProfesor"></input>
</form>


<!-- 
Se tiene que declarar el boton como de tipo "button" pq por defecto,
los botones adentro de un formulario son de tipo submit, por lo tanto
esto causaba que el formulario se enviara cuando necesitabamos cerrar 
el modal. Esta explicacion sirve para todos los botones de ceerrar que hay-->
<button class="btn-Cerrar" type="button">Cerrar</button>
    </div>
</dialog>


<dialog>
    <form id="form-registro" class="registro-div recursos-form" action="../backend/functions/recursos_func.php" method="POST">
    <h1>Registro de Recursos</h1><hr> 
       <div class="div-labels">
        <label for="name" class="label">Nombre:</label>
            <input class="input-register" type="text"  name="name" id="nombreRecurso" maxlength="40" minlength="3"  required placeholder="Ingresa nombre">
        </div>
        <div class="div-labels">
        <label for="estado" class="label">Estado:</label>
            <select class="input-register" type="text"  name="estado" id="estadoRecurso" maxlength="20" minlength="8"  required placeholder="">
                <option value=""></option>
                <option value="uso">Uso</option>
                <option value="libre">Libre</option>
                <option value="roto">Roto</option>
            </select>
        </div>
 
        <!-- que hace esto??

        En resumen, agarra a todos los nombres de espacios fisicos que hay y los pone
        como opciones. Si se selecciona x salon, se pasa su id como value asi se registra
        la conexion en la BD  -->
        <div class="div-labels">
            <label for="pertenece" class="label">Pertenece a:</label>
            <select name="pertenece" id="pertenece" type="text" class="input-register">
                <option value=""></option>

                <!-- ARREGLAR!! SI SE SELECCIONA GENERAL NO FUNCIONA!! -->
                <option value="general">General</option>
                <?php while ($row = mysqli_fetch_array($sql)): ?>
                    <option value="<?= $row['id_espacio']?>"><?= $row['nombre']?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="div-labels">
        <label for="tipo" class="label">Tipo:</label>
            <select class="input-register" type="text"  name="tipo" id="tipo" maxlength="20" minlength="8"  required placeholder="">
                <option value=""></option>
                <option value="interno">Interno</option>
                <option value="externo">Externo</option>
            </select>
        <div class="div-botones-register">
            <input  id="envRegistro" class="btn-enviar-registro" type="submit" value="Registrar" name="registrarRecurso"></input>
    </form>
        <button class="btn-Cerrar" type="button">Cerrar</button>
        </div>
</dialog>
</div>

     <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

<?php elseif(isset($_SESSION['ci'])):?>

    <?php include 'nav.php'; ?>
    <body>
        
            <br><br><br><br><br><br><br>
    <div class="contenido">
        <h1>HOLA, SOS UN PROFESOR!!</h1>
    </div>

    <footer id="footer" class="footer">
        <p> &copy; <b> 2025 ITSP. Todos los derechos reservados </b></p>
    </footer>

<?php else:?>
    <?php include_once('error.php')?>
<?php endif;?>

<!--    Cierre de Ventanas Emergentes    -->
    



    <!-- Bootstrap -->
    <script src="js/Register-Modal.js"></script>
    <script src="js/Validaciones-registro.js"></script>

    <!-- Sweet alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
    </html>