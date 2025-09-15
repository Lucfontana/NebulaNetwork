<?php
    include('../PRUEBA_BASE_DE_DATOS/conexion.php');           

    $connect = conectar_a_bd();

    $id = $_GET['id'];

    $sql = "DELETE FROM asignaturas WHERE id_asignatura='$id'";

    $query = mysqli_query($connect, $sql);

    if ($query) {
    Header("location: ../asignaturas.php");
    }
?>