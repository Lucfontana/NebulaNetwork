<?php
// Incluye el archivo de conexión a la base de datos
include_once('../../../db/conexion.php');

// Establece la conexión
$con = conectar_a_bd();

// Configurar zona horaria
date_default_timezone_set('America/Montevideo');

// Indicar que la respuesta de este PHP es un JSON
header('Content-Type: application/json');

// Verifica que sea una petición POST y que existan los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_solicita']) && isset($_POST['id_recurso'])) {
    
    // Sanitiza y valida los datos recibidos
    $id_solicita = (int)$_POST['id_solicita'];
    $id_recurso = (int)$_POST['id_recurso'];
    
    // Verifica que el préstamo existe y no ha sido devuelto
    $existe_prestamo = verificar_prestamo_pendiente($con, $id_solicita);
    
    // Procesa la devolución
    $resultado_devolucion = procesar_devolucion_recurso($con, $existe_prestamo, $id_solicita, $id_recurso);
    
    // Se codifica el resultado como JSON
    echo json_encode($resultado_devolucion);
    
} else {
    // Si no se recibieron los datos correctos
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido o datos faltantes'
    ]);
}

function verificar_prestamo_pendiente($con, $id_solicita) {
    // Consulta para verificar que existe y no tiene hora de devolución
    $consulta = "SELECT id_solicita, hora_vuelta 
                 FROM su_administra_recursos 
                 WHERE id_solicita = ? 
                 AND hora_vuelta IS NULL";
    
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("i", $id_solicita);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifica existencia y devuelve true/false
    return ($result->num_rows > 0);
}

function procesar_devolucion_recurso($con, $existe_prestamo, $id_solicita, $id_recurso) {
    // Array para almacenar la respuesta
    $respuesta_json = array();
    
    if ($existe_prestamo) {
        
        // Obtiene la hora actual en formato MySQL
        $hora_actual = date('Y-m-d H:i:s');
        
        // 1. Actualiza la hora de devolución en su_administra_recursos
        $query_devolucion = "UPDATE su_administra_recursos 
                                SET hora_vuelta = ? 
                                WHERE id_solicita = ?";
        
        $stmt = $con->prepare($query_devolucion);
        $stmt->bind_param("si", $hora_actual, $id_solicita);
        $stmt->execute();
        
        // 2. Actualiza el estado del recurso a 'libre'
        $query_recurso = "UPDATE recursos 
                            SET estado = 'libre' 
                            WHERE id_recurso = ?";
        
        $stmt2 = $con->prepare($query_recurso);
        $stmt2->bind_param("i", $id_recurso);
        $stmt2->execute();

        // Respuesta exitosa
        $respuesta_json['success'] = true;
        $respuesta_json['message'] = 'Devolución registrada exitosamente';
        $respuesta_json['hora_devolucion'] = date('d/m/Y H:i', strtotime($hora_actual));
        
    } else {
        // El préstamo no existe o ya fue devuelto
        $respuesta_json['success'] = false;
        $respuesta_json['message'] = 'El recurso ya fue devuelto o la solicitud no existe';
    }
    
    return $respuesta_json;
}

?>