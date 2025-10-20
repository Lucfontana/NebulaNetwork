<?php

include_once ('../../db/conexion.php');

$con = conectar_a_bd();

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

if(isset($_POST['registrarCursos'])){
    $nombre_curso = strip_tags(trim($_POST['name']));
    $capacidad = (int)$_POST['capacity'];
    $id_orientacion = (int)$_POST['orientacion_en'];

    $existe = consultar_si_existe_curso($con, $nombre_curso);

    $insert_cursos = insert_datos_cursos($con, $existe, $nombre_curso, $capacidad, $id_orientacion);

    //Se codifica el resultado de la insercion como json
    echo json_encode($insert_cursos);

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
function insert_datos_cursos($con, $existe, $nombre_curso, $capacidad, $id_orientacion){
    // Array para almacenar la respuesta
    $respuesta_json = array();
    
    if ($existe == false){
        $query_insertar = "INSERT INTO cursos (nombre, capacidad, id_orientacion) VALUES (?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("sii", $nombre_curso, $capacidad, $id_orientacion);
        $stmt->execute();

        $respuesta_json['estado'] = 1;
        $respuesta_json['mensaje'] = "Insertado correctamente";

    } else {

        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Este curso ya existe";
    }

    return $respuesta_json;

}

?>