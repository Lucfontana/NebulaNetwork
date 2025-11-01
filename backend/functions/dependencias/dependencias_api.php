<?php

include_once("../../db/conexion.php");
include_once("dependencias_func.php");

$con = conectar_a_bd();

header('Content-Type: application/json');

// Fijarse si se mando el formulario por post y registrar la dependencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrarDependencia'])) {
    
    // Sanitizar y validar datos
    $ci_profesor = (int)$_POST['profesor_asignado'];
    $id_asignatura = (int)$_POST['asignatura_dada'];
    $id_espacio = (int)$_POST['salon_ocupado'];
    $id_curso = (int)$_POST['curso_dictado'];
    $horarios = $_POST['hora_profesor_da_clase']; // Array
    $dia_dictado = trim(strip_tags($_POST['dia_dictado']));
    
    // Llamar a la función principal
    $resultado = registrar_dependencia_completa(
        $con, 
        $ci_profesor, 
        $id_asignatura, 
        $horarios, 
        $id_espacio, 
        $id_curso,
        $dia_dictado
    );
    
    echo json_encode($resultado);
    
    $con->close();
} else if (isset($_POST['eliminarClase'])) {
    $curso_eliminar = $_POST['curso_eliminar'];
    $hora_eliminar = $_POST['hora_eliminar'];
    $dia = $_POST['dia_eliminar'];

    $resultado = eliminar_dependencia($con, $curso_eliminar, $hora_eliminar, $dia);

    echo json_encode($resultado);
}
?>