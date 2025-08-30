<?php

require("conexion.php");

$con = conectar_a_bd();

if (isset($_POST["registrarAsignatura"])){
    $nombreAsignatura = $_POST["nombreAsignatura"];

    $existeAsignatura = consultar_existencia($con, $nombreAsignatura);

    insertar_datos($con, $nombreAsignatura, $existeAsignatura);
}

function consultar_existencia($con, $elemento_a_buscar){
    $result_existencia = mysqli_query($con, "SELECT nombre FROM asignaturas WHERE nombre = '$elemento_a_buscar'");

    if(mysqli_num_rows($result_existencia) > 0){
        return true;
    } 
    else
    {
        return false;
    }
}

function insertar_datos($con, $dato_a_insertar, $existeAsignatura){
    if ($existeAsignatura == false){
        $consulta_insertar = "INSERT INTO asignaturas (nombre) VALUES '$dato_a_insertar'";

        if (mysqli_query($con, $consulta_insertar)){
            $salida = consultar_datos($con);
            echo $salida;
        } else {
            echo "Error: " . $consulta_insertar . "<br>" . mysqli_error($con);
        }
    } else {
        echo "La asignatura ya existe";
    }
}

function consultar_datos($con){
    $consulta = "SELECT * FROM asignaturas";
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

?>