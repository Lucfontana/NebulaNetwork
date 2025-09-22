<?php

//Se incluye el archivo de la conexion para declarar su variable
include_once('../../db/conexion.php');


$con = conectar_a_bd(); //se declara la conexion

//La utilidad principal de esta funcion es, verificar si existe un id_dicta que tenga como FK la id_asignatura y
//ci_profesor que el usuario eligio. Si no existe la CREA, y si existe, devuelve la id_dicta que ya existe para
//no volver a crearla.
function seleccionar_id_dicta($con, $id_asignatura, $ci_profesor){ // Se pasa como argumento los datos a buscar

//Se declara la consulta SQL, los ? son los valores que EL USUARIO INGRESA
$consulta = "SELECT id_dicta FROM profesor_dicta_asignatura WHERE id_asignatura = (?) AND ci_profesor = (?)";

$stmt = $con->prepare($consulta); //Se prepara la consulta
$stmt->bind_param("ii", $id_asignatura, $ci_profesor); //Se dice que, los dos datos son ints ('i' es para int, 's' para 
                                                        // string, por eso dice 'ii', ambos son int) y que los 
                                                       //simbolos de pregunta corresponden a id_asignatura y ci_profesor.

$stmt->execute(); //Se ejecuta la consulta
$result = $stmt->get_result(); //Se devuelve el resultado obtenido.

    //Si hay mas de una coincidencia, quiere decir que ese elemento existe.
    if ($result->num_rows > 0)
    {
        return busca_id_dicta($con, $id_asignatura, $ci_profesor); //Se devuelve la ID del elemento que existe
    } 
    else //Si no hay coincidencias, se hace una insercion para crear esta ID y se devuelve la misma
    {
        //Pasa lo mismo que como cuando usas el SELECT, lo unico que se añade aca es el stmt execute 
        $sql = "INSERT INTO profesor_dicta_asignatura (ci_profesor, id_asignatura) VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $ci_profesor, $id_asignatura);
        $stmt->execute(); //Se indica que el INSERT se ejecute
        return busca_id_dicta($con, $id_asignatura, $ci_profesor); //Se devuelve la ID de lo que recien se inserto

    }
}

function busca_id_dicta($con, $id_asignatura, $ci_profesor){
        $sql2 = "SELECT id_dicta FROM profesor_dicta_asignatura WHERE ci_profesor = ? AND id_asignatura = ?";
        $stmt2 = $con->prepare($sql2);
        $stmt2->bind_param("ii", $ci_profesor, $id_asignatura);
        $stmt2->execute();

        //Se guarda el resultado que haya traido el SELECT 
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc(); //Se transforma ese resultado en un array asociativo 
        return $row['id_dicta']; //Devolvemos simplemente el valor de la ID
}

function insert_dicta_ocupa_espacio($con, $id_dicta, $id_espacio){
        $query_insertar = "INSERT INTO dicta_ocupa_espacio (id_dicta, id_espacio) VALUES (?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("ii", $id_dicta, $id_espacio);
        $stmt->execute();
        echo "Dicta ocupa espacio insertado correctamente";
}

function insert_dicta_en_curso($con, $id_dicta, $id_curso){
        $query_insertar = "INSERT INTO dicta_en_curso (id_dicta, id_curso) VALUES (?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("ii", $id_dicta, $id_curso);
        $stmt->execute();
        echo "Dicta en curso insertado correctamente";
}

?>