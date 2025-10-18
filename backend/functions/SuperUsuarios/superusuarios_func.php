<?php

include_once ('../../db/conexion.php');
include_once('../Profesores/profesores_func.php');

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

$con = conectar_a_bd();

if (isset($_POST['registrarSuperuser'])){
    //Declarar las variables con sus respectivos filtros
    $ci = trim((int)$_POST['CI']);
    $password = trim(password_hash($_POST['password'], PASSWORD_DEFAULT));
    $nombre = strip_tags(trim($_POST['name']));
    $apellido = strip_tags(trim($_POST['apellido'])); 
    $email_superusuario = strip_tags(trim($_POST['email']));
    $acceso = (int)$_POST['acceso'];

    //Verifica si existe el usuario, llamando ala funcion y guarda el valor en una variable
    //la funcion viene del archivo 'profesores_func', verificar en ese archivo para los comentarios
    $consultar_existencia = consultar_si_existe_usuario($con, $ci);

    $insert_superusuario = insert_datos_superuser($con, $consultar_existencia, $ci, $password, $nombre, $apellido, $acceso, $email_superusuario);

    echo json_encode($insert_superusuario);
    //TO DO: Llamar a  verificaciones para cada variable
}




function insert_datos_superuser($con, $existe, $ci, $password, $nombre, $apellido, $acceso, $email_superusuario){
    // Array para almacenar la respuesta
    $respuesta_json = array();

    //$existe[0] se usa para saber si existe como profesor, $existe[1] se usa
    //para saber si existe, pero como SUPERUSUARIO.

    //Si no existe como ninguno de los usuarios, se guarda el valor como superusuario
    if (!$existe[0] && !$existe[1]){
        $query_insertar = "INSERT INTO superUsuario (id_superusuario, pass_superusuario, nombre, apellido, nivel_acceso, email_superusuario) VALUES ( ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("isssis", $ci, $password, $nombre, $apellido, $acceso, $email_superusuario);
        $stmt->execute();

        $respuesta_json['estado'] = 1;
        $respuesta_json['mensaje'] = "Insertado correctamente";
    } else if ($existe[0]){

        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Esta CI ya esta registrada como un profesor";
        
    } else if ($existe[1]){

        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "Este superusuario ya existe";
    }

    return $respuesta_json;
}

?>