<?php


function conectar_a_bd(){

    //Se declara la conexion, comenzando con el nombre del host, nombre del usuario, contraseña, nombre de BD
$conexion = mysqli_connect("localhost", "root", "", "nebulanetwork", "3306");

    if (!$conexion) {
    //Termina la ejecucion del codigo actual, seria lo mismo que exit()
    die("Hubo un error de conexion: " . mysqli_connect_error());
}

//Se devuelve la conexion como resultado
return $conexion;

}

$con = conectar_a_bd();