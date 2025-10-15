<?php

include_once("../../db/conexion.php");

$con = conectar_a_bd();

function consultar_si_existe_horario($con) {
    //Declaramos la consulta que vamos a hacer a la base de datos para despues pasarla como variable
    $consulta = "SELECT * FROM horarios";
    //Se prepara la consulta diciendole que el ? corresponde a la $hora_inicio que es de tipo string, y se la ejecuta.  
    $stmt = $con->prepare($consulta);
    $stmt->execute();

    $result = $stmt->get_result();

    //Si hay mas de una coincidencia, quiere decir que la hora_inicio existe 
    //si no, quiere decir q es nuevo y que se puede agregar sin problemas    
    return ($result->num_rows > 0);

}

function borrar_horarios($con){
        $consulta = "DELETE FROM horarios";
        $stmt = $con->prepare($consulta);
        $stmt->execute();

        //Se tiene que eliminar todo lo relacionado a "dicta" tambien
        $eliminar_dicta = "DELETE FROM profesor_dicta_asignatura";
        $stmt2 = $con->prepare($eliminar_dicta);
        $stmt2->execute();

        $respuesta_json['estado'] = '1';
        $respuesta_json['mensaje'] = 'Horarios eliminados exitosamente';    

        return $respuesta_json;
}

function registrarHorarios($horaInicio, $horaFinal, $conexion, $existe) {
    if (!$existe){
        $respuesta_json = array();

        // Convertir las horas a objetos DateTime
        
        $inicio = new DateTime($horaInicio);

        $hora_inicio = (int)$inicio->format('H');

        $final = new DateTime($horaFinal);

        
        // Validar que la hora inicial sea menor que la final
        if ($inicio >= $final) {
            $respuesta_json['estado'] = '0';
            $respuesta_json['mensaje'] = 'La hora de inicio debe ser menor que la hora final';    
            return $respuesta_json;
        }

        if ($hora_inicio <= 6){
            $respuesta_json['estado'] = '0';
            $respuesta_json['mensaje'] = 'La hora de inicio debe ser despues de las 6';    
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

    } else {
        $respuesta_json['estado'] = '2';
        $respuesta_json['mensaje'] = 'Los horarios ya existen <br>  <strong>Aviso:</strong> Si desea crear los horarios de nuevo,
                                     los horarios actuales y todo lo relacionado a estos será eliminado, ¿Desea continuar?';
        return $respuesta_json;
    }
}


?>