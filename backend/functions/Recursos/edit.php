<?php

include_once ('../../db/conexion.php');
 
$connect = conectar_a_bd();

//Obtiene los valores enviados mediante un formulario
$id = $_POST['id_recurso'];
$id_espacio = $_POST['id_espacio'];
$name = $_POST['nombre'];
$estado = $_POST['estado'];
$tipo = $_POST['tipo'];

//consulta SQL preparada que actualizará un registro en la tabla recursos
$consulta = "UPDATE recursos SET nombre = ?, tipo = ?, estado = ? WHERE id_recurso=?";
$stmt = $con->prepare($consulta);
$stmt->bind_param("sssi", $name, $tipo, $estado, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) { //Evalúa si la consulta modificó alguna fila:
    echo json_encode(["success" => true, "message" => "Recurso editado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
$stmt->close();

?>
