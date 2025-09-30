<?php

include_once ('../db/conexion.php');

$con = conectar_a_bd();

//Para comentarios, ir a superusuarios_func o profesores_func
if(isset($_POST['registrarRecurso'])){
    $nombre = strip_tags(trim($_POST['name']));
    $estado = strip_tags(trim($_POST['estado']));
    $tipo = strip_tags(trim($_POST['tipo']));

    //Recibe la FK del salon
    $pertenece_a = (int)$_POST['pertenece'];

    $existe =consultar_si_existe_recurso($con, $nombre);

    insert_datos_recursos($con, $existe, $nombre, $estado, $tipo, $pertenece_a);
}

function consultar_si_existe_recurso($con, $nombre){
//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT nombre FROM recursos WHERE nombre = ?";

$stmt = $con->prepare($consulta);

$stmt->bind_param("s", $nombre);

$stmt->execute();

$result = $stmt->get_result();

    //Verifiac existencia y devuelve true/false
    if ($result->num_rows > 0){
        return true;
    } else {
        return false;
    }
}

function insert_datos_recursos($con, $existe, $nombre, $estado, $tipo, $pertenece_a){
    if ($existe == false){
        // No incluir id_recurso - es AUTO_INCREMENT (pero si la del espacio pq es la fk que recibe el formulario)
        $query_insertar = "INSERT INTO recursos (id_espacio, nombre, estado, tipo) VALUES ( ?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        
        // 4 parámetros, 4 tipos
        $stmt->bind_param("isss", $pertenece_a, $nombre, $estado, $tipo);
        
        if ($stmt->execute()) {
            echo "Insertado correctamente";
        } else {
            echo "Error al insertar: " . $stmt->error;
        }
        
    } else {
        echo "Este recurso ya existe";
    }
}

?>