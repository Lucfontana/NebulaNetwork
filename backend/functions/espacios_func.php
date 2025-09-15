<?php

include_once ('../db/conexion.php');

$con = conectar_a_bd();


//Para comentarios, ir a cursos_func.php o superusuarios_func.php
if (isset($_POST['registrarEspacio'])){

   $nombre = strip_tags(trim($_POST['name']));
   $capacity = (int)$_POST['capacity'];
   $equipo = strip_tags(trim($_POST['equip']));
   $tipo = strip_tags(trim($_POST['tipo'])); 

   $existe = consultar_si_existe_espacio($con, $nombre);

   insert_datos_espacios($con, $existe, $nombre, $capacity, $equipo, $tipo);
}

function consultar_si_existe_espacio($con, $nombre){
//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT nombre FROM espacios_fisicos WHERE LOWER(nombre) = LOWER(?)";

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

function insert_datos_espacios($con, $existe, $nombre, $capacity, $equipo, $tipo){
    if ($existe == false){
        // No incluir id_espacio - es AUTO_INCREMENT
        $query_insertar = "INSERT INTO espacios_fisicos (nombre, capacidad, equipamiento, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        
        // 4 parámetros, 4 tipos
        $stmt->bind_param("siss", $nombre, $capacity, $equipo, $tipo);
        
        if ($stmt->execute()) {
            echo "Insertado correctamente";
        } else {
            echo "Error al insertar: " . $stmt->error;
        }
        
    } else {
        echo "Este salon ya existe";
    }
}

?>