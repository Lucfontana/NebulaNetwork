<?php
include_once("../../helpers.php");

//Aclara que la hora con la que se va a trabajar es la hora de uruguay
date_default_timezone_set('America/Montevideo');

function registrar_reserva_completa($con, $ci_profesor, $fecha_reservar, $horas_reservar, $dia_semana_seleccionado, $id_espacio)
{
    $respuesta_json = [];
    $error = false;
    $mensaje_error = "";
    $horarios_insertados = 0;

    $con->begin_transaction();

    $fecha_actual = obtener_hora_calendario(); //Viene de helpers.php, devuelve un timestamp
    $fecha_actual_datetime = new DateTime();
    $fecha_actual_datetime->setTimestamp($fecha_actual); // Así se convierte timestamp a DateTime

    //Se hace lo mismo con la fecha de reserva, pero se lo pasa a un objeto "DateTime" para poder hacer la comparacion
    $fecha_reserva_datetime = new DateTime($fecha_reservar);
    $fecha_reserva_datetime->format("Y-m-d");

    // Verificación: comprobar que la fecha no sea anterior a la actual
    if (!$error) {
        if ($fecha_reserva_datetime < $fecha_actual_datetime) {
            $error = true;
            $mensaje_error = "Tienes que elegir un día próximo al actual";
        }
    }

    //Se verifica que un horario no se haya repetido dos veces
    if (!$error) {
        //funcion de helpers.php
        $horarios_duplicados = horarios_duplicados($horas_reservar);
        // Si hay al menos un horario duplicado en el array
        if (count($horarios_duplicados) > 0) {
            // Marcamos que hay error para detener el proceso
            $error = true;

            // Creamos un mensaje de error personalizado
            $mensaje_error = "No puedes seleccionar el mismo horario más de una vez";
        }
    }

    //Verifica que el profesor no tenga una inasistencia en el horario que desea reservar
    if (!$error) {
        //realiza la validacion de inasistencia y trae las variables de: $error y $mensaje_error por si llega a haber
        //List se usa para traer resultados de una funciopn que devuelve mas de un valor
        //                          esta funcion VIENE DE HELPERS.PHP
        list($error, $mensaje_error) = verificar_inasistencia($con, $fecha_reservar, $horas_reservar, $ci_profesor);
    }

    if (!$error) {
        $horarios_con_reserva = array();

        //Query que trae las reservaas segun una fecha, un horario y un profesor
        $query_verificar_reserva = "SELECT h.hora_inicio, h.hora_final FROM reservas_espacios re
                                INNER JOIN horarios h ON h.id_horario = re.id_horario
                                WHERE re.fecha_reserva = ? AND re.id_horario = ? AND re.ci_profesor = ?";

        $stmt_reservas = $con->prepare($query_verificar_reserva);

        //Para cada horario ingresado, se realiza una query
        foreach ($horas_reservar as $id_horario) {
            $stmt_reservas->bind_param("sii", $fecha_reservar, $id_horario, $ci_profesor);
            $stmt_reservas->execute();
            $resultado_verificar_reserva = $stmt_reservas->get_result();

            //Si el numero de rows (filas) es distinto a 0 (quiere decir que hay inasistencia) se guarda en el array de horarios_con_falta
            if ($resultado_verificar_reserva->num_rows !== 0) {
                $horario_info = $resultado_verificar_reserva->fetch_assoc();
                $horarios_con_reserva[] = $horario_info['hora_inicio'] . '-' . $horario_info['hora_final'];
            }
        }

        $stmt_reservas->close();

        // Verificar DESPUES del foreach si hay mas de un valor en el array. En caso positivo se marca error
        if (count($horarios_con_reserva) > 0) {
            $error = true;
            $mensaje_error = "El profesor ya tiene reservas registradas en los siguientes horarios: " . implode(", ", $horarios_con_reserva);
        }
    }

    //Inserta los datos como una reserva
    if (!$error) {
        // Preparar las queries una sola vez fuera del bucle
        $sql_dicta = "SELECT pda.id_dicta, c.id_curso
                  FROM profesor_dicta_asignatura pda
                  JOIN dicta_en_curso denc ON denc.id_dicta = pda.id_dicta
                  JOIN cursos c ON c.id_curso = denc.id_curso
                  WHERE pda.ci_profesor = ?
                    AND denc.id_horario = ?
                    AND denc.dia = ?";

        $stmt_dicta = $con->prepare($sql_dicta);

        $query_insertar_reserva = "INSERT INTO reservas_espacios (ci_profesor, id_dicta, id_curso, id_espacio, id_horario, fecha_reserva, dia)
                               VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt_insert = $con->prepare($query_insertar_reserva);

        foreach ($horas_reservar as $id_horario) {
            $stmt_dicta->bind_param("iis", $ci_profesor, $id_horario, $dia_semana_seleccionado);
            $stmt_dicta->execute();
            $result = $stmt_dicta->get_result();

            if ($row = $result->fetch_assoc()) {
                $id_dicta = intval($row['id_dicta']);
                $id_curso = intval($row['id_curso']);
            } else {
                // No hay coincidencia usar 0 en ambos campos
                $id_dicta = null;
                $id_curso = null;
                $error = true;
                $mensaje_error = 'Horario inválido: No estás dictando clases en una de las horas seleccionadas.';
            }

            $stmt_insert->bind_param("iiiiiss", $ci_profesor, $id_dicta, $id_curso, $id_espacio, $id_horario, $fecha_reservar, $dia_semana_seleccionado);

            if ($stmt_insert->execute()) {
                $horarios_insertados++;
            } else {
                // Si falla la insercion quiere decir que no existe algún id_dicta 
                $error = true;
                $mensaje_error = "Error al insertar reserva: No tienes clases a la hora que quieres reservar.";
                break;
            }
        }
    }

    // Confirmar o revertir segun corresponda
    if ($error) {
        $con->rollback();
        $respuesta_json = [
            'estado' => 0,
            'mensaje' => $mensaje_error,
            'datos' => $datos ?? null
        ];
    } else {
        $con->commit();
        $respuesta_json = [
            'estado' => 1,
            'mensaje' => "Reserva registrada correctamente. Se asignaron {$horarios_insertados} horarios."
        ];
    }

    return $respuesta_json;
}
?>