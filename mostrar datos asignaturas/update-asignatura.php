<?php
    include('../PRUEBA_BASE_DE_DATOS/conexion.php');

    $connect = conectar_a_bd();

    $id = $_GET['id'];

    $sql = "SELECT * FROM asignaturas WHERE id_asignatura='$id'";
    $query = mysqli_query($connect, $sql);
    $row = mysqli_fetch_array($query)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="edit-asignatura.php" method="POST">
    <h1>Registro de Asignaturas</h1><hr>
        <div class="div-labels">
            <input class="input-register" type="hidden" name="id_asignatura" id="id" value="<?= $row['id_asignatura']?>">
        </div>
        <label for="nombre" class="label">Nombre:</label>
            <input type="text"  name="nombre" id="name" maxlength="20" minlength="3"  required placeholder="Ingresa nombre" value="<?= $row['nombre']?>">
        </div>
    <input type="submit" value="Actualizar Infomacion"></input>
    </form>
</body>
</html>
