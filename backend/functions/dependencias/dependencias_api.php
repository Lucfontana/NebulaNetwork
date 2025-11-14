<?php

include_once("../../db/conexion.php");
include_once("dependencias_func.php");

$con = conectar_a_bd();

//Indica que la respuesta del script será JSON, no HTML.
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
    //trim(strip_tags()) limpia el campo dia_dictado de espacios y etiquetas HTML.
    
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
    
    echo json_encode($resultado);//Convierte el resultado de la función a formato JSON y lo envía como respuesta
    
    $con->close(); //Cierra la conexión con la base de datos.

} else if (isset($_POST['eliminarClase'])) { //Si en el POST se recibe eliminarClase
    $curso_eliminar = $_POST['curso_eliminar'];
    $hora_eliminar = $_POST['hora_eliminar'];
    $dia = $_POST['dia_eliminar'];

    //después de obtener los datos para identificarlas, llama a eliminar_dependencia() para borrarla.
    $resultado = eliminar_dependencia($con, $curso_eliminar, $hora_eliminar, $dia);

    echo json_encode($resultado);
}
?>