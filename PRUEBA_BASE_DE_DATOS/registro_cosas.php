<?php
$insercion_dateishons = null;
require("conexion.php");

$con = conectar_a_bd();

if (isset($_POST["registrarAsignatura"])){
    $nombreAsignatura = $_POST["nombreAsignatura"];

    $dato_a_seleccionar = 'nombre';
    $tabla_a_buscar = 'asignaturas';

    $existeAsignatura = consultar_existencia($con, $nombreAsignatura, $dato_a_seleccionar, $tabla_a_buscar);

    $insercion_dateishons = insertar_datos($con, $nombreAsignatura, $existeAsignatura, $dato_a_seleccionar, $tabla_a_buscar);

}

function consultar_existencia($con, $elemento_a_buscar, $dato_a_seleccionar, $tabla_a_buscar){
    $result_existencia = mysqli_query($con, "SELECT $dato_a_seleccionar FROM $tabla_a_buscar WHERE $dato_a_seleccionar = '$elemento_a_buscar'");

    if(mysqli_num_rows($result_existencia) > 0){
        return true;
    } 
    else
    {
        return false;
    }
}

//$dato_a_insertar = "$dato1" . ", " . "$dato2" . ", " . "$dato3";

function insertar_datos($con, $dato_a_insertar, $existeAsignatura, $dato_a_seleccionar, $tabla_a_buscar){
    if ($existeAsignatura == false){
        $consulta_insertar = "INSERT INTO $tabla_a_buscar ($dato_a_seleccionar) VALUES ('$dato_a_insertar')";

        if (mysqli_query($con, $consulta_insertar)){
            $salida = consultar_datos($con, $tabla_a_buscar);
            echo $salida;
        } else {
            echo "Error: " . $consulta_insertar . "<br>" . mysqli_error($con);
        }
    } else {
        echo "Su dato ya existe";
    }
}

function consultar_datos($con, $tabla_a_buscar){
    $consulta = "SELECT * FROM $tabla_a_buscar";
    $resultado = mysqli_query($con, $consulta);

    $salida = "";

    if (mysqli_num_rows($resultado) > 0){
        while ($fila = mysqli_fetch_assoc($resultado)){
            $salida .= "ID: " . $fila["id_asignatura"] . "- Nombre: " . $fila["nombre"] . "<br>" . "<hr>";
        }
    } else {
        $salida = "Sin datos";
    }

    return $salida;
}

mysqli_close($con);

    include 'mostrarIndfo.php';

?>