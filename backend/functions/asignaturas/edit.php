<?php
include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

//Recibe los parametros mediante metodo post del formulario y los recibe segun el name=""
$id = $_POST['id_asignatura'];
$name = $_POST['nombre'];


//Consulta preparada de edit(update) al cual le cargamos los parametros
$consulta = "UPDATE asignaturas SET nombre=? WHERE id_asignatura=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("si", $name ,$id); // Aca se cargan los parametros, a los cuales les decimos que tipo de datos son y los cargamos mediante variables
$stmt->execute();


//Si la cantidad de registros editados es mayor a 0 entonces el edit se ha ejecutado correctamente
if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Asignatura editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
//lo cerramos para que no ocupe espacio, por el limite
$stmt->close();

?>
