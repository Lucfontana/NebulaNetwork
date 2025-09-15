<?php
include_once ('../../db/conexion.php');
$connect = conectar_a_bd();
$id = $_GET['id'];

$sql = "SELECT * FROM asignaturas WHERE id_asignatura='$id'";
$query = mysqli_query($connect, $sql);
$row2 = mysqli_fetch_array($query)

?>

<html>

<div id="overlay-edit" class="overlay-edit">
        <form action="/backend/functions/mostar datos asignaturas/edit-asignatura.php" method="POST">
            <h1>Registro de Asignaturas</h1>
            <hr>
            <div class="div-labels">
                <input class="input-register" type="hidden" name="id_asignatura" id="id" value="<?= $row['id_asignatura'] ?>">
            </div>
            <div>
                <label for="nombre" class="label">Nombre:</label>
                <input type="text" name="nombre" id="name" maxlength="20" minlength="3" required placeholder="Ingresa nombre" value="<?= $row['nombre'] ?>">
            </div>
            <div>
            <input type="submit" value="Actualizar Infomacion" id="actualizar" href="../backend/functions/mostrar datos asignaturas/update-asignatura.php?id=<?=$row['id_asignatura']?>"></input>
            <input type="button" value="Cancelar" id="cancelarEdit"></input>
            </div>
        </form>
    </div>

    </html>