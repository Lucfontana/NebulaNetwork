<?php

include_once ('../db/conexion.php');

$con = conectar_a_bd(); 

if (isset($_POST['registroProfesor'])){
    //TO DO:Se deberian primero declarar las variables crudas, pasarlas por las validaciones y despues sanitizarlas (creo).

    //Trim es funcion de PHP, saca los espacios al inicio y final de una string
    //strip_tags tambien es funcion de PHP, borra todos los caracteres de php
    //password_hash encripta la contrasena
    //filter_var aplica ciertos filtros dependiendo de lo que querramos sanitizar
    $ci = trim((int)$_POST['CI']);
    $password = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $nombre = strip_tags(trim($_POST['name']));
    $apellido = strip_tags(trim($_POST['apellido']));
    
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $fecha_nac = trim($_POST['nac']);
    $direccion = strip_tags(trim($_POST['direc']));

    //Llamar a la funcion para verificar q el profe existe
    $existe = consultar_si_existe_profe($con, $ci);

    //Insertar los datos
    insert_datos_profe($con, $existe, $ci, $password, $nombre, $apellido, $email, $fecha_nac, $direccion);
    
}
function consultar_si_existe_profe($con, $ci){
//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT ci_profesor FROM profesores WHERE ci_profesor = ?";
//Se prepara la consulta diciendole que el ? corresponde a la $ci que es de tipo int, y se la ejecuta.  
$stmt = $con->prepare($consulta);
$stmt->bind_param("i", $ci);
$stmt->execute();

$result = $stmt->get_result();

//Si hay mas de una coincidencia, quiere decir que la ci existe (por lo tanto, el PROFESOR EXISTE)
//si no, quiere decir q es nuevo y que se puede agregar sin problemas    
    if ($result->num_rows > 0){
        return true;
    } else {
        return false;
    }

}

//Se pasan los valores como parametros y se ingresan en la bd
function insert_datos_profe($con, $existe, $ci, $password, $nombre, $apellido, $email, $fecha_nac, $direccion){
    if ($existe == false){
        $query_insertar = "INSERT INTO profesores (ci_profesor, pass_profesor, nombre, apellido, email, fecha_nac, direccion) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("issssss", $ci, $password, $nombre, $apellido, $email, $fecha_nac, $direccion);
        $stmt->execute();
        echo "Insertado correctamente";
    } else {
        echo "Este superusuario ya existe";
    }

}

?>