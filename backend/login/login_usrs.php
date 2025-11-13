<?php
// CRÍTICO: Capturar output para evitar que warnings/errores rompan el JSON
ob_start();

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

//Se inlcuye la conexion a bd y se la declara como variable para usarla en las funciones
include_once('../db/conexion.php');
$con = conectar_a_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Si se envia el formulario por post y se envia el login, se guardan los datos ingresados
    //por el usuario y se los envia a la funcion "login".
    if (isset($_POST['loginUsuario'])) {

        $ci_usuario = (int)$_POST["CI"];
        $password = $_POST['contrasena'];

        $login = login($con, $ci_usuario, $password);
        
        // CRÍTICO: Limpiar buffer antes de enviar JSON
        ob_end_clean();
        echo json_encode($login);
        exit();
    }
}

//Trae la informacion de un profesor o de un administrador
function traer_datos_usuario($con, $ci_usuario)
{
    //Consulta que trae informacion del profesor que coincida con lo ingresado por el usuario
    $query_profesores = "SELECT * FROM profesores WHERE ci_profesor = ?";
    $stmt = $con->prepare($query_profesores);
    $stmt->bind_param("i", $ci_usuario);
    $stmt->execute();

    //Se guarda el resultado de la consulta en una variable 
    $result_prof = $stmt->get_result();

    //Consulta que trae informacion del superusuario que coincida con lo ingresado por el usuario
    $query_superusuarios = "SELECT * FROM superUsuario WHERE id_superusuario = ?";
    $stmt = $con->prepare($query_superusuarios);
    $stmt->bind_param("i", $ci_usuario);
    $stmt->execute();

    //Se guarda el resultado de la consulta en una variable
    $result_su = $stmt->get_result();

    //Se convierten los resultados en arrays asociativos para poder acceder a ellos mas facilmente
    //Ademas, al pasarlos a arrays, la informacion queda mas manipulable
    $row_prof = mysqli_fetch_array($result_prof);
    $row_su = mysqli_fetch_array($result_su);

    //Si hay una coincidencia con la consulta de profesores quiere decir que el profesor 
    //existe, por lo que se devuelve su informacion mas legible en otro array asociativo
    if ($result_prof->num_rows > 0) {
        return [
            'tipo' => 'profesor',
            'ci' => $row_prof['ci_profesor'],
            'nombre' => $row_prof['nombre'],
            'apellido' => $row_prof['apellido'],
            'pass_profesor'=> $row_prof['pass_profesor'],
            'email' => $row_prof['email'],
            'fecha_nac' => $row_prof['fecha_nac'],
            'direccion' => $row_prof['direccion']
        ];
        //De lo contrario, verifica si existe un superusuario que coincida con esa informacion y hace lo mismo que arriba
    } else if ($result_su->num_rows > 0) {
        return [
            'tipo' => 'superusuario',
            'ci' => $row_su['id_superusuario'],
            'pass_superusuario' => $row_su['pass_superusuario'], 
            'nombre' => $row_su['nombre'],
            'apellido' => $row_su['apellido'],
            'nivel_acceso' => $row_su['nivel_acceso']
        ];
        //Si no existe ninguno de los dos, simplemente se devuelve null
    } else {
        return null;
    }
}

//Esta función sirve para verificar si un usuario existe (profesor o superusuario), comprobar su contraseña 
//y crear una sesión si los datos son correctos.
function login($con, $ci_usuario, $password)
{
    // CRÍTICO: Inicializar la variable 
    //Se crea un array asociativo llamado $respuesta_json que almacenará el resultado del login.
    $respuesta_json['estado'] = 0;
    $respuesta_json['mensaje'] = 'Error desconocido';

    //Se trae el array asociativo con la informacion del usuario que exista
    $datos_usuario = traer_datos_usuario($con, $ci_usuario);

    //Se inicia la sesion
    session_start();


    //Si los datos del usuario no estan vacios, el codigo prosigue (que no sea null)
    if ($datos_usuario !== null) {
        //Si el usuario ingresado es un profesor, se ejecuta el siguiente codigo

        //Se obtiene la contraseña encriptada que está guardada en la base de datos
        if ($datos_usuario['tipo'] === 'profesor') {
            $password_bd = $datos_usuario['pass_profesor'];

            //compara la contraseña ingresada con la versión de la base de datos.
            if (password_verify($password, $password_bd)) {
                //Se pasan los valores a la sesion para que se acceda desde cualquier pagina
                $_SESSION['ci'] = $ci_usuario;
                $_SESSION['nombre_usuario'] = $datos_usuario['nombre'];
                $_SESSION['apellido'] = $datos_usuario['apellido'];
                $_SESSION['pass_profesor'] = $datos_usuario['pass_profesor'];

                //Redirecciona al index

                //se actualiza la respuesta JSON
                $respuesta_json['estado'] = 1;
                $respuesta_json['mensaje'] = "Sesion iniciada correctamente como PROFESOR";
            } else {
                $respuesta_json['estado'] = 0;
                $respuesta_json['mensaje'] = "Contraseña incorrecta";
            }
            
            //Si no es profesor pero sí es superusuario
        } else if ($datos_usuario['tipo'] === 'superusuario') {
            $password_bd = $datos_usuario['pass_superusuario'];

            //Si la contraseña es correcta, se guardan los datos en la sesión
            if (password_verify($password, $password_bd)) {
                $_SESSION['ci'] = $ci_usuario;
                $_SESSION['nombre_usuario'] = $datos_usuario['nombre'];
                $_SESSION['pass_superusuario'] = $datos_usuario['pass_superusuario'];
                $_SESSION['apellido'] = $datos_usuario['apellido'];
                $_SESSION['nivel_acceso'] = $datos_usuario['nivel_acceso'];

                $respuesta_json['estado'] = 1;
                $respuesta_json['mensaje'] = "Sesion iniciada correctamente como SUPERUSUARIO";                
            } else {
                $respuesta_json['estado'] = 0;
                $respuesta_json['mensaje'] = "Contraseña incorrecta";
            }
        }
    } else { // Si el usuario no existe
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = "El usuario ingresado no existe";
    }
    
    return $respuesta_json;
}