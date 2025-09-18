<?

include_once('../../db/conexion.php');

$con = conectar_a_bd();

function consultar_si_existe_horario($con, $hora_inicio) {
    //Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
    $consulta = "SELECT hora_inicio FROM horarios WHERE hora_inicio = ?";
    //Se prepara la consulta diciendole que el ? corresponde a la $hora_inicio que es de tipo string, y se la ejecuta.  
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("s", $hora_inicio);
    $stmt->execute();

    $result = $stmt->get_result();

    //Si hay mas de una coincidencia, quiere decir que la hora_inicio existe 
    //si no, quiere decir q es nuevo y que se puede agregar sin problemas    
        if ($result->num_rows > 0){
            return true;
        } else {
            return false;
        }

}

//Se pasan los valores como parametros y se ingresan en la bd
function insert_datos_horas($con, $existe, $hora_inicio, $hora_final){
    if ($existe == false){
        $query_insertar = "INSERT INTO horarios (hora_inicio, hora_final) VALUES (?, ?)";
        $stmt = $con->prepare($query_insertar);
        $stmt->bind_param("ss", $hora_inicio, $hora_final);
        $stmt->execute();
        echo "Insertado correctamente";
    } else {
        echo "Este horario ya existe";
    }
}

?>