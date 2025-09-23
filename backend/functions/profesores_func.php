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
    $existe = consultar_si_existe_usuario($con, $ci);

    //Insertar los datos
    insert_datos_profe($con, $existe, $ci, $password, $nombre, $apellido, $email, $fecha_nac, $direccion);
    
}
function consultar_si_existe_usuario($con, $ci){

            //$existencia[0] se usa para saber si existe como profesor, $existencia[1] se usa
            //para saber si existe, pero como SUPERUSUARIO.
$existencia = [false, false];

//Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
$consulta = "SELECT ci_profesor FROM profesores WHERE ci_profesor = ?";

//Preparamos la consulta, pasamos como argumentos:
//$con (es la variable que tiene la conexion a la bd y que definimos al principio)
//$consulta (es la variable q tiene la consulta sql)
$stmt = $con->prepare($consulta);

//Le decimos que, el signo de la pregunta que tiene la consulta, es un int ("i")
//Y que corresponde a la variable que tiene la cedula de identidad
$stmt->bind_param("i", $ci);

//Se ejecuta la consulta
$stmt->execute();

//Se guarda el resultado de la consulta en la variable $result
$result = $stmt->get_result();

//Si hay mas de una coincidencia, quiere decir que la ci existe (por lo tanto, el PROFESOR EXISTE)
//si no, quiere decir q es nuevo y que se puede agregar sin problemas  

//Por lo tanto, si hay coincidencias, a $existencia[0] le ponemos el valor 'true', 
//lo que indica que hay un profesor con esas credenciales
    if ($result->num_rows > 0){
        $existencia[0] = true;
    } 

//A su vez, se necesita hacer una consulta para ver si no existe un SUSPERUSUARIO con esa CI (En teoria,
// no puede haber un PROFESOR que sea SUPERUSUARIO al mismo tiempo... creo)

//CONSULTA DE SUPERUSUARIO: Misma logica que la consulta de profesor
$consulta2 = "SELECT id_superusuario FROM superUsuario WHERE id_superusuario = ?";
$stmt2 = $con->prepare($consulta2);
$stmt2->bind_param("i", $ci);
$stmt2->execute();
$result2 = $stmt2->get_result();  

    //Este if, hace lo mismo que el if de profesor, con la diferencia que altera el valor del 
    //segundo elemento del arreglo ($existencia[1]) el cual corresponde a los superUsuarios
    if ($result2->num_rows > 0){
        $existencia[1] = true;
    }

    //Se devuelve el valor del arreglo para utilizarlo en futuras funciones
    return $existencia;
}

//Se pasan los valores como parametros y se ingresan en la bd
function insert_datos_profe($con, $existe, $ci, $password, $nombre, $apellido, $email, $fecha_nac, $direccion){
    if ($existe[0] == false && $existe[1] == false){
        $query_insertar = "INSERT INTO profesores (ci_profesor, pass_profesor, nombre, apellido, email, fecha_nac, direccion) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("issssss", $ci, $password, $nombre, $apellido, $email, $fecha_nac, $direccion);
        $stmt->execute();
        echo "Insertado correctamente";
    } else if ($existe[0] == true){
        echo "Este profesor ya existe";
    } else if ($existe[1] == true){
        echo "Esta CI ya existe registrado como otro tipo de usuario";
    }

}

?>