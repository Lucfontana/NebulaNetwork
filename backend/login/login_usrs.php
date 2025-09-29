<?php

//Se inlcuye la conexion a bd y se la declara como variable para usarla en las funciones
include_once('../db/conexion.php');
$con = conectar_a_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //Si se envia el formulario por post y se envia el login, se guardan los datos ingresados
    //por el usuario y se los envia a la funcion "login".
    if(isset($_POST['loginUsuario'])){

        $ci_usuario = (int)$_POST["CI"];
        $password = $_POST['contrasena'];

        login($con, $ci_usuario, $password);
        
    }

}

//Trae la informacion de un profesor o de un administrador
function traer_datos_usuario($con, $ci_usuario) {

        //Consulta que trae informacion del profesor que coincida con lo ingresado por el usuario
        $query_profesores = "SELECT * FROM profesores WHERE ci_profesor = $ci_usuario";
        $stmt = $con->prepare($query_profesores);
        $stmt->execute();

        //Se guarda el resultado de la consulta en una variable 
        $result_prof = $stmt->get_result();

        
        //Consulta que trae informacion del superusuario que coincida con lo ingresado por el usuario
        $query_superusuarios = "SELECT * FROM superUsuario WHERE id_superusuario = $ci_usuario";
        $stmt = $con->prepare($query_superusuarios);
        $stmt->execute();

        //Se guarda el resultado de la consulta en una variable
        $result_su = $stmt->get_result();

        //Se convierten los resultados en arrays asociativos para poder acceder a ellos mas facilmente
        //Ademas, al pasarlos a arrays, la informacion queda mas manipulable
        $row_prof = mysqli_fetch_array($result_prof);
        $row_su = mysqli_fetch_array($result_su);

        //Si hay una coincidencia con la consulta de profesores quiere decir que el profesor 
        //existe, por lo que se devuelve su informacion mas legible en otro array asociativo
        if($result_prof->num_rows > 0){
            return [
                'ci' => $row_prof['ci_profesor'],
                'nombre' => $row_prof['nombre'],
                'apellido' => $row_prof['apellido'],
                'email' => $row_prof['email'],
                'fecha_nac' => $row_prof['fecha_nac'],
                'direccion' => $row_prof['direccion']

            ];
          //De lo contrario, verifica si existe un superusuario que coincida con esa informacion y hace lo mismo que arriba
        } else if ($result_su->num_rows > 0){
            return [
                'ci' => $row_su['id_superusuario'],
                'nombre' => $row_su['nombre'],
                'apellido' => $row_su['apellido'],
                'nivel_acceso' => $row_su['nivel_acceso']

            ];
          //Si no existe ninguno de los dos, simplemente se devuelve null
        } else {
            return null;
        }
    }

function login($con, $ci_usuario, $password){

    //Se trae el array asociativo con la informacion del usuario que exista
    $datos_usuario = traer_datos_usuario($con, $ci_usuario);

    //Se inicia la sesion
    session_start();

    //Se trae la informacion del usuario ingresado (verificando ya sea si es profesor o superusuario).
    $query_profesores = "SELECT * FROM profesores WHERE ci_profesor = $ci_usuario";
    $stmt = $con->prepare($query_profesores);
    $stmt->execute();
    $result_login_prof = $stmt->get_result();

    $query_superusuarios = "SELECT * FROM superUsuario WHERE id_superusuario = $ci_usuario";
    $stmt = $con->prepare($query_superusuarios);
    $stmt->execute();
    $result_login_su = $stmt->get_result();

    //Si los datos del usuario no estan vacios, el codigo prosigue (que no sea null)
    if (isset($datos_usuario)){
        //Si el usuario ingresado es un profesor, se ejecuta el siguiente codigo
        if($result_login_prof->num_rows > 0){

            //se hace un array asociativo con los resultados de la consulta anterior
            $fila_profesores = mysqli_fetch_assoc($result_login_prof);

            $password_bd = $fila_profesores['pass_profesor']; //Se trae la contrasena

            if(password_verify($password, $password_bd)){ //Se verifica que la contrasena hasheada 
                                                          //sea igual a la que esta guardada en la bd

                //Se pasan los valores a la sesion para que se acceda desde cualquier pagina
                $_SESSION['ci'] = $ci_usuario;
                $_SESSION['nombre_usuario'] = $datos_usuario['nombre'];
                $_SESSION['apellido'] = $datos_usuario['apellido'];

                //Redirecciona al index
                echo "Sesion iniciada correctamente como PROFESOR";
                header("Location: ../../../frontend/index.php");
                exit();
            } else { //Si pa contrasena es incorrecta, devuelve mensaje de error
                echo "Contraseña incorrecta";
            }
            //Si el usuario no es un profesor, chequea que sea un superusuario y actua igual que el if de arriba
        } else if ($result_login_su->num_rows > 0){
            $fila_su = mysqli_fetch_assoc($result_login_su);

            $password_bd = $fila_su['pass_superusuario'];

            if(password_verify($password, $password_bd)){
                $_SESSION['ci'] = $ci_usuario;
                $_SESSION['nombre_usuario'] = $datos_usuario['nombre'];  
                $_SESSION['apellido'] = $datos_usuario['apellido'];
                $_SESSION['nivel_acceso'] = $datos_usuario['nivel_acceso'];

                echo "Sesion iniciada correctamente como SUPERUSUARIO";
                header("Location: ../../../frontend/index.php");
            } else {
                echo "Contraseña incorrecta";
            }
        }
    } else { //Si no es ni usuario ni profesor, muestra mensaje de que no existe
        echo "El usuario ingresado no existe";
    }
}
?>