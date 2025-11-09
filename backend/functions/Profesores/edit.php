<?php

//Este script sirve para editar (actualizar) los datos de un profesor en una base de datos

include_once ('../../db/conexion.php');

header('Content-Type: application/json'); // Muy importante

$con = conectar_a_bd(); // usa $con, no $connect

$ci = $_POST['ci_profesor'];
$name = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];   
$fecha_nac = $_POST['fnac'];
$direccion = $_POST['direccion'];


//query SQL preparada para actualizar un registro de la tabla profesores.
$consulta = "UPDATE profesores SET nombre = ?, apellido = ?, email = ?, fecha_nac = ?, direccion = ? WHERE ci_profesor = ?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("sssssi", $name, $apellido, $email, $fecha_nac, $direccion, $ci);
$stmt->execute();

if ($stmt->affected_rows > 0) {//Revisa si alguna fila fue modificada:
    echo json_encode(["success" => true, "message" => "Profesor editado correctamente"]);
    //si hay lineas modificadas, la actualización fue exitosa
} else { //Si no, significa que no se cambió ningún registro
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}

$stmt->close();
$con->close();
