<?php

include_once("../../db/conexion.php");

$con = conectar_a_bd();

function registrarHorarios($horaInicio, $horaFinal, $conexion) {
    $respuesta_json = array();

    // Convertir las horas a objetos DateTime
    
    $inicio = new DateTime($horaInicio);
    $final = new DateTime($horaFinal);

    
    // Validar que la hora inicial sea menor que la final
    if ($inicio >= $final) {
        $respuesta_json['estado'] = '0';
        $respuesta_json['mensaje'] = 'La hora de inicio debe ser menor que la hora final';    
        return $respuesta_json;
    }

    //Se usa CLONE porque $inicio es un objeto.
    $horaActual = clone $inicio;
    
    // Preparar la consulta, para despues pasarle los valores con el bind param
    $sql = "INSERT INTO horarios (hora_inicio, hora_final, tipo) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    //Si sale algo mal en el statement (una tabla no existe, se escribio algo mal, etc)
    if (!$stmt) {
        $respuesta_json['estado'] = '0';
        $respuesta_json['mensaje'] = 'Error al preparar la consulta: ' . $conexion->error; 
        return $respuesta_json;
    }
    
    // Insertar registros mientras la hora actual sea menor a la hora final
    while ($horaActual < $final) {
        //Primero, se inserta la CLASE
        $horaFinalClase = clone $horaActual;

        //Se suman 45 minutos a la hora final de la clase
        $horaFinalClase->modify('+45 minutes');
        
        // Si la hora final de la clase excede el límite, ajustarla
        if ($horaFinalClase > $final) {
            $horaFinalClase = clone $final;
        }
        
        // Insertar clase            Se le pasa el formato de la hora (hora:minutos:segundos)
        $horaInicioStr = $horaActual->format('H:i:s');
        $horaFinalStr = $horaFinalClase->format('H:i:s');
        //tipoClase tiene que ser declarada antes porque el bind_param SOLO acepta variables
        $tipoClase = "clase";
        
        $stmt->bind_param('sss', $horaInicioStr, $horaFinalStr, $tipoClase);
        
        if (!$stmt->execute()) {
            $respuesta_json['estado'] = '0'; 
            $respuesta_json['mensaje'] = 'Error al insertar clase: ' . $stmt->error;

            //Se cierra el statement para limpiar memoria
            $stmt->close();
            return $respuesta_json;
        }
        
        // Avanzar a la hora de inicio del recreo
        $horaActual = clone $horaFinalClase;
        
        // Insercion del recreo
        // Solo insertar recreo si hay espacio antes del final
        $horaFinalRecreo = clone $horaActual;
        $horaFinalRecreo->modify('+5 minutes');
        
        if ($horaFinalRecreo <= $final) {
            $horaInicioStr = $horaActual->format('H:i:s');
            $horaFinalStr = $horaFinalRecreo->format('H:i:s');
            $tipoClase = 'recreo';
            
            $stmt->bind_param('sss', $horaInicioStr, $horaFinalStr, $tipoClase);
            
            if (!$stmt->execute()) {
                $respuesta_json['estado'] = '0'; 
                $respuesta_json['mensaje'] = 'Error al insertar recreo: ' . $stmt->error;
                $stmt->close();
                return $respuesta_json;
            }
            
            // Avanzar 5 minutos después del recreo
            $horaActual->modify('+5 minutes');
        } else {
            // Si no hay espacio para el recreo, terminamos
            break;
        }
    }
    
    $stmt->close();
    
    $respuesta_json['estado'] = '1';
    $respuesta_json['mensaje'] = 'Horarios registrados exitosamente';
    return $respuesta_json;
}

?>