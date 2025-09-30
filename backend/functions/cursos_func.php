<?php

include_once ('../db/conexion.php');

$con = conectar_a_bd();

if(isset($_POST['registrarCursos'])){
    $nombre_curso = strip_tags(trim($_POST['name']));
    $capacidad = (int)$_POST['capacity'];

    $existe = consultar_si_existe_curso($con, $nombre_curso);

    insert_datos_cursos($con, $existe, $nombre_curso, $capacidad);

}
function consultar_si_existe_curso($con, $nombre_curso){
//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT nombre FROM cursos WHERE LOWER(nombre) = LOWER(?)";
//Se prepara la consulta diciendole que el ? corresponde al $nombre_curso que es de tipo string, y se la ejecuta.  
$stmt = $con->prepare($consulta);
$stmt->bind_param("s", $nombre_curso);
$stmt->execute();

$result = $stmt->get_result();

//Si hay mas de una coincidencia, quiere decir que el nombre existe (por lo tanto, el CURSO EXISTE)
//si no, quiere decir q es nuevo y que se puede agregar sin problemas    
    if ($result->num_rows > 0){
        return true;
    } else {
        return false;
    }

}

//Se pasan los valores como parametros y se ingresan en la bd
function insert_datos_cursos($con, $existe, $nombre_curso, $capacidad){
    if ($existe == false){
        $query_insertar = "INSERT INTO cursos (nombre, capacidad) VALUES (?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("si", $nombre_curso, $capacidad);
        $stmt->execute();
        echo "Insertado correctamente";
    } else {
        echo "Este curso ya existe";
    }

}

?>