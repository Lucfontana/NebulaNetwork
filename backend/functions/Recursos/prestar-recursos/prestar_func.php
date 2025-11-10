<?php

include_once('../../../db/conexion.php');

$con = conectar_a_bd();

// Ingresar profesor solicita recurso (pronto)

// Si tipo recurso esta en uso, error de que no se puede prestar (pronto)

// Alterar tabla a recurso para cambiar su estado a uso (pronto)

// Guardar superusuario administra recurso, pasando la id de SOLICITA y guardando la HORA ACTUAL (pronto)


//Se podria simplificar la BD??


//El usuario puede editar los valores con "inspeccionar", por lo que 
//nos fijamos si los valores que se mandaron realmente existen

//Busca en la tabla profesores si existe un profesor con la cédula ingresada.
function existe_profesor($con, $profesor){
    //Consulta
    $consulta_profesor = "SELECT ci_profesor FROM profesores WHERE (ci_profesor) = (?)";
    $stmt = $con->prepare($consulta_profesor);
    $stmt->bind_param("i", $profesor);
    $stmt->execute();
    $result = $stmt->get_result();

    return ($result->num_rows > 0);
    //Devuelve true si existe, false si no.
}


//Verifica que el recurso exista y no esté actualmente en uso.
function existe_recurso($con, $recurso){

    //Consulta
    //Agarra los recursos y se fija si esta en uso o no
    $consulta_recurso = "SELECT id_recurso FROM recursos WHERE (id_recurso) = (?) AND estado != 'uso'";
    $stmt = $con->prepare($consulta_recurso);
    $stmt->bind_param("i", $recurso);
    $stmt->execute();
    $result = $stmt->get_result();

    //Si esta en uso o no existe, devolveria false, si no devuelve true
    return ($result->num_rows > 0);
}

function insertar_profesor_solicita_recurso($con, $existe_profesor, $existe_recurso, $profesor, $recurso){
    if ($existe_profesor && $existe_recurso){ //Si el profesor y recurso existen

        //Se inserta el registro en la tabla profesor_solicita_recurso
        $query_insertar = "INSERT INTO profesor_solicita_recurso (ci_profesor, id_recurso) VALUES (?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("ss", $profesor, $recurso);
        $stmt->execute();
        // $con->insert_id;

        //Trae la id de solicita y la devuelve
        //Se ordena de forma descendente para traer la ultima id ingresada
        $traer_id = "SELECT id_solicita FROM profesor_solicita_recurso WHERE (id_recurso) = (?) AND (ci_profesor) = (?) ORDER BY id_solicita DESC";
        $stmt = $con->prepare($traer_id);
        $stmt->bind_param("ii", $recurso, $profesor);
        $stmt->execute();

        $result = $stmt->get_result();
        // Obtener el valor de id_solicita
        if ($row = $result->fetch_assoc()) { //intenta obtener una fila de la consulta.
            $id_solicita = $row['id_solicita'];//Si sí hay una fila, $row será un array. Si no hay filas, $row será false.
        } else {
            return null; //Si no se encontró ninguna fila (la consulta no devolvió nada), la función devuelve null.
        }

        //se actualiza el estado del recurso
        //se cambia el estado del recurso a "uso"
        $actualizar_tabla = "UPDATE recursos SET estado = 'uso' WHERE id_recurso = ?";
        $stmt = $con->prepare($actualizar_tabla);
        $stmt->bind_param("i", $recurso);
        $stmt->execute();

    } else {
        $id_solicita = null; //Si algo falla (profesor o recurso no válidos), devuelve null.
    }
    return $id_solicita;
}

//Esta función guarda en la base de datos quién prestó el recurso, cuándo y a qué solicitud corresponde.
function insertar_su_presta_recurso($con, $id_solicita, $superusuario, $hora_prestado){
    if (isset($id_solicita)){ //Se verifica si $id_solicita existe

        //Se prepara una sentencia SQL para insertar un nuevo registro en la tabla
        //que guarda las acciones de los superusuarios sobre los recursos.
        $query_insertar = "INSERT INTO su_administra_recursos (id_solicita, id_superusuario, hora_presta) VALUES (?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("iis", $id_solicita, $superusuario, $hora_prestado);
        $stmt->execute();

        $respuesta_json['estado'] = 1;
        $respuesta_json['mensaje'] = "Recurso prestado exitosamente";

    } else {

        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "El profesor o recurso asignado no existe, vuelva a intentarlo.";
    }

    return $respuesta_json;
}


?>