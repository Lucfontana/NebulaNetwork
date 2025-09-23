<?php

include_once ('../db/conexion.php');
include_once('profesores_func.php');

$con = conectar_a_bd();

// $mensaje = '';
// $tipo_mensaje = '';

if (isset($_POST['registrarSuperuser'])){
    //Declarar las variables con sus respectivos filtros
    $ci = trim((int)$_POST['CI']);
    $password = trim(password_hash($_POST['password'], PASSWORD_DEFAULT));
    $nombre = strip_tags(trim($_POST['name']));
    $apellido = strip_tags(trim($_POST['apellido'])); 
    $acceso = (int)$_POST['acceso'];

    //Verifica si existe el usuario, llamando ala funcion y guarda el valor en una variable
    //la funcion viene del archivo 'profesores_func', verificar en ese archivo para los comentarios
    $consultar_existencia = consultar_si_existe_usuario($con, $ci);

    insert_datos_superuser($con, $consultar_existencia, $ci, $password, $nombre, $apellido, $acceso);

    //TO DO: Llamar a los filtros y verificaciones para cada variable
}




function insert_datos_superuser($con, $existe, $ci, $password, $nombre, $apellido, $acceso){
    if (!$existe[0] && !$existe[1]){
        $query_insertar = "INSERT INTO superUsuario (id_superusuario, pass_superusuario, nombre, apellido, nivel_acceso) VALUES ( ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("isssi", $ci, $password, $nombre, $apellido, $acceso);
        $stmt->execute();
        echo "Insertado correctamente";
    } else if ($existe[0]){
        echo "Esta CI ya esta registrada como un profesor";
    } else if ($existe[1]){
        echo "Este superusuario ya existe";
    }

}

?>