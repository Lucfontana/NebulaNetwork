<?php

//Acá se hace la conexión con la base de datos
function conectar_a_bd(){

//Se declara la conexion, comenzando con el nombre del host, nombre del usuario, contraseña, nombre de BD, y el puerto
$conexion = mysqli_connect("localhost", "root", "", "nebulanetwork", "3306");

    if (!$conexion) {
    //Si hubo un error, termina la ejecucion del codigo actual, seria lo mismo que exit()
    die("Hubo un error de conexion: " . mysqli_connect_error());
}

//Se devuelve la conexion como resultado
return $conexion;

}

//Llama a la función y guarda la conexión 
$con = conectar_a_bd();