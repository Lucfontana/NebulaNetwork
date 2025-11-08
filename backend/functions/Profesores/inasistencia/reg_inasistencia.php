<?php

//funciones para registrar inacistencia de profes

include_once("../../../db/conexion.php");
include_once("../../../queries.php");
include_once("../../../helpers.php");

date_default_timezone_set('America/Montevideo');

$con = conectar_a_bd();

function registrar_falta_completa($con, $ci_profesor, $horarios, $dia, $fecha_inasistencia)
{
    $respuesta_json = array();
    $error = false;
    $mensaje_error = "";

    $con->begin_transaction();

    $fecha_actual = obtener_hora_calendario(); //Viene de helpers.php, devuelve un timestamp
    $fecha_actual_datetime = new DateTime();
    $fecha_actual_datetime->setTimestamp($fecha_actual); // Así se convierte timestamp a DateTime

    //Se hace lo mismo con la fecha de inasistencia, pero se lo pasa a un objeto "DateTime" para poder hacer la comparacion
    $fecha_inasistencia_datetime = new DateTime($fecha_inasistencia);
    $fecha_inasistencia_datetime->format("Y-m-d");


    //Verificacion 1: Verifica que la fecha ingresada por el usuario no sea anterior a la fecha actual
    if (!$error) {
        if ($fecha_inasistencia_datetime < $fecha_actual_datetime) {
            $error = true;
            $mensaje_error = "Tienes que elegir un dia proximo al actual";
        }
    }

    //Paso 1: Verifica que el profesor realmente exista
    if (!$error) {
        $result = query_profesor_especifico($con, $ci_profesor);

        //Si no hay coincidencias, quiere decir que no existe el profesor ingresado, por lo que se marca
        //error y el mensaje de error, este funcionamiento tienen todas las validaciones de existencia
        if ($result->num_rows == 0) {
            $error = true;
            $mensaje_error = "El profesor no existe";
        }
    }

    //Paso 1.5: Antes de registrar la inasistencia, se verifica que la hora ingresada no 
    //estuviera previamente registrada como una hora en la que se iba a faltar

    if (!$error) {
        //realiza la validacion de inasistencia y trae las variables de: $error y $mensaje_error por si llega a haber
        //List se usa para traer resultados de una funciopn que devuelve mas de un valor
        //                          esta funcion VIENE DE HELPERS.PHP
        list($error, $mensaje_error) = verificar_inasistencia($con, $fecha_inasistencia, $horarios, $ci_profesor);
    }

    //Paso 1.7: Verificar que las horas no esten repetidas

    if (!$error) {
        // array_count_values() cuenta cuántas veces aparece cada valor en el array
        // Si $horarios = [1, 2, 1, 3], entonces $horarios_contados = [1 => 2, 2 => 1, 3 => 1]
        // Esto significa: el horario 1 aparece 2 veces, el 2 aparece 1 vez, el 3 aparece 1 vez
        $horarios_contados = array_count_values($horarios);

        // Array para guardar los IDs de horarios que están duplicados
        $horarios_duplicados = array();

        // Recorrer el array de horarios contados
        // $id_horario es la clave (el ID del horario)
        // $cantidad es el valor (cuántas veces aparece ese ID)
        foreach ($horarios_contados as $id_horario => $cantidad) {
            // Si un horario aparece más de una vez, es un duplicado
            if ($cantidad > 1) {
                // Agregamos ese ID al array de duplicados
                $horarios_duplicados[] = $id_horario;
            }
        }

        // Si hay al menos un horario duplicado en el array
        if (count($horarios_duplicados) > 0) {
            // Marcamos que hay error para detener el proceso
            $error = true;

            // Creamos un mensaje de error personalizado
            $mensaje_error = "No puedes seleccionar el mismo horario más de una vez";
        }
    }



    //Paso 2: Verificar que en las horas ingresadas por el usuario, el profesor realmente tenga clases

    //Por cada hora ingresada se ejecuta una query, si en esas queries no hay resultado, el 
    //horario se guarda en una variable de horarios_sin_clase. Si esta variable no esta vacia, 
    //hay error y el programa se detiene.
    if (!$error) {
        $horarios_sin_clase = array();

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

            //Si no hay coincidencias, significa que el profesor no tiene clases a esa gora
            if ($resultado_verificar_clase->num_rows == 0) {
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
    if (!$error) {
        $horarios_insertados = 0;

        $query_insertar_inasistencia = "INSERT INTO inasistencia (ci_profesor, id_horario, fecha_inasistencia) values (?, ?, ?)";
        $stmt_falta = $con->prepare($query_insertar_inasistencia);

        foreach ($horarios as $id_horario) {
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

    if ($error) {

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