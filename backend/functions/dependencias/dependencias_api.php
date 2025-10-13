<?php

include_once("../../db/conexion.php");
include_once("dependencias_func.php");

$con = conectar_a_bd();

// Fijarse si se mando el formulario por post y registrar la dependencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrarDependencia'])) {
    
    header('Content-Type: application/json');
    
    // Sanitizar y validar datos
    $ci_profesor = (int)$_POST['profesor_asignado'];
    $id_asignatura = (int)$_POST['asignatura_dada'];
    $id_espacio = (int)$_POST['salon_ocupado'];
    $id_curso = (int)$_POST['curso_dictado'];
    $horarios = $_POST['hora_profesor_da_clase']; // Array
    
    // Llamar a la función principal
    $resultado = registrar_dependencia_completa(
        $con, 
        $ci_profesor, 
        $id_asignatura, 
        $horarios, 
        $id_espacio, 
        $id_curso
    );
    
    echo json_encode($resultado);
    
    $con->close();
}
?>