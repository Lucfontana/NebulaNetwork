<?php 

include_once("../../../db/conexion.php");
include_once("../../../queries.php");

$con = conectar_a_bd();

function registrar_falta_completa($con, $ci_profesor, $horarios, $dia, $fecha_inasistencia) {
    $respuesta_json = array();
    $error = false;
    $mensaje_error = "";

    $con->begin_transaction();

    if (!$error){
        $result = query_profesor_especifico($con, $ci_profesor);

        //Si no hay coincidencias, quiere decir que no existe el profesor ingresado, por lo que se marca
        //error y el mensaje de error, este funcionamiento tienen todas las validaciones de existencia
        if ($result ->num_rows == 0){
            $error = true;
            $mensaje_error = "El profesor no existe";
        }
    }

    //Paso 2: Verificar que en las horas ingresadas por el usuario, el profesor realmente tenga clases

    //Por cada hora ingresada se ejecuta una query, si en esas queries no hay resultado, el 
    //horario se guarda en una variable de horarios_sin_clase. Si esta variable no esta vacia, 
    //hay error y el programa se detiene.
    if (!$error) {
    $horarios_sin_clase = array();
    $horarios_validos = array();

    // Query para verificar que el profesor tenga clase en esos horarios
    $query_verificar_clase = "SELECT h.id_horario, h.hora_inicio, h.hora_final 
                                FROM cumple c
                                INNER JOIN profesor_dicta_asignatura pda ON c.id_dicta = pda.id_dicta
                                INNER JOIN horarios h ON c.id_horario = h.id_horario
                                WHERE pda.ci_profesor = ? AND h.id_horario = ? AND c.dia = ?";

    $stmt_verificar_clase = $con->prepare($query_verificar_clase);

        //Para cada horario que ingreso el usuario, se ejecuta un statement para verificar si en 
        //ese horario el profesor esta dictando una clase o no (el usuario se puede hacer el loquito 
        // con el inspeccionar y cambiar las id de los horarios que aparecen)
        foreach ($horarios as $id_horario) {
            $stmt_verificar_clase->bind_param("iis", $ci_profesor, $id_horario, $dia);
            $stmt_verificar_clase->execute();
            $resultado_verificar_clase = $stmt_verificar_clase->get_result();
            
            //Si hay una coincidencia, significa que el horario existe
            if ($resultado_verificar_clase->num_rows > 0) {
                // El profesor SÍ tiene clase en este horario - válido para inasistencia
                $horario_info = $resultado_verificar_clase->fetch_assoc();
                $horarios_validos[] = $horario_info;

            //Si el profesor no tiene clase en ese horario, el resultado se va a guardar en una variable    
            } else {
                // El profesor NO tiene clase en este horario - error
                $horarios_sin_clase[] = $id_horario;
            }
        }

    $stmt_verificar_clase->close();

        // Si hay horarios donde el profesor no tiene clase, mostrar error
        if (count($horarios_sin_clase) > 0) {
            $error = true;
            $mensaje_error = "El profesor no tiene clases asignadas en los siguientes horarios: " . implode(", ", $horarios_sin_clase);
        }
    }

    //Paso 3: Guardar las inasistencias
    if (!$error){
        $horarios_insertados = 0;

        $query_insertar_inasistencia = "INSERT INTO inasistencia (ci_profesor, id_horario, fecha_inasistencia) values (?, ?, ?)";
        $stmt_falta = $con->prepare($query_insertar_inasistencia);

        foreach ($horarios as $id_horario){
            $stmt_falta->bind_param("iis", $ci_profesor, $id_horario, $fecha_inasistencia);

            if ($stmt_falta->execute()) {
                $horarios_insertados++;
            } else {
                $error = true;
                $mensaje_error = "Error al insertar horarios";
                break;
            }
            
        }
        $stmt_falta->close();
    }


    // ========== PARTE IMPORTANTE!!!! ==========
    //Revertir cambios: A lo largo de toda la funcion fuimos insertando cosas en la base de datos, pero si
    //una cosa llegara a salir mal, esas inserciones ya se quedarian ahi. Por lo tanto, el manejo de errores 
    //con lo de las transacciones que se explico al principio es importante aca.

    //Si llega a haber error, se puede hacer rollback. Esto quiere decir que esas inserciones se van a borrar
    //asi no quedan hechas al pedo.

    if ($error){

        // Hubo algún error, revertir todo
        $con->rollback();
        
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = $mensaje_error;
        $respuesta_json['datos'] = null;
          
    } else {
                
        // todo bien, confirmar cambios
        $con->commit();
        
        $respuesta_json['estado'] = 1;
        $respuesta_json['mensaje'] = "Inasistencia registrada correctamente. Se asignaron {$horarios_insertados} faltas.";
        $respuesta_json['datos'] = array(
            'horarios_insertados' => $horarios_insertados
        );
    }

    return $respuesta_json;
}

?>