<?php

include ('conexion.php');

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
    $consultar_existencia = consultar_si_existe_suser($con, $ci);

    insert_datos_superuser($con, $consultar_existencia, $ci, $password, $nombre, $apellido, $acceso);

    //TO DO: Llamar a los filtros y verificaciones para cada variable
}

function consultar_si_existe_suser($con, $ci){
//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT id_superusuario FROM superUsuario WHERE id_superusuario = ?";

//Preparamos la consulta, pasamos como argumentos:
//$con (es la variable que tiene la conexion a la bd y que definimos al principio)
//$consulta (es la variable q tiene la consulta sql)
$stmt = $con->prepare($consulta);

//Le decimos que, el signo de la pregunta que tiene la consulta, es un int ("i")
//Y que corresponde a la variable que tiene la cedula de identidad
$stmt->bind_param("i", $ci);

//Se ejecuta la consulta
$stmt->execute();

$result = $stmt->get_result();

//Si hay mas de una coincidencia, quiere decir que la ci existe (por lo tanto, el SUPERUSUARIO EXISTE)
//si no, quiere decir q es nuevo y que se puede agregar sin problemas    
    if ($result->num_rows > 0){
        return true;
    } else {
        return false;
    }


}


function insert_datos_superuser($con, $existe, $ci, $password, $nombre, $apellido, $acceso){
    if ($existe == false){
        $query_insertar = "INSERT INTO superUsuario (id_superusuario, pass_superusuario, nombre, apellido, nivel_acceso) VALUES ( ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("isssi", $ci, $password, $nombre, $apellido, $acceso);
        $stmt->execute();
        echo "Insertado correctamente";
    } else {
        echo "Este superusuario ya existe";
    }

}

?>