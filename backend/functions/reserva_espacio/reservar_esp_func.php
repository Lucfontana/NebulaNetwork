<?php
function registrar_reserva_completa($con, $ci_profesor, $fecha_reservar, $horas_reservar, $dia_semana_seleccionado, $id_espacio) {
    $respuesta_json = [];
    $error = false;
    $mensaje_error = "";
    $horarios_insertados = 0;

    $con->begin_transaction();

    // Preparar las queries una sola vez fuera del bucle
    $sql_dicta = "SELECT pda.id_dicta, c.id_curso
                  FROM profesor_dicta_asignatura pda
                  JOIN dicta_en_curso denc ON denc.id_dicta = pda.id_dicta
                  JOIN cursos c ON c.id_curso = denc.id_curso
                  WHERE pda.ci_profesor = ?
                    AND denc.id_horario = ?
                    AND denc.dia = ?";

    $stmt_dicta = $con->prepare($sql_dicta);
    if (!$stmt_dicta) {
        die("Error preparando SQL dicta: " . $con->error);
    }

    $query_insertar_reserva = "INSERT INTO reservas_espacios 
        (ci_profesor, id_dicta, id_curso, id_espacio, id_horario, fecha_reserva, dia)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt_insert = $con->prepare($query_insertar_reserva);
    if (!$stmt_insert) {
        die("Error preparando SQL reserva: " . $con->error);
    }

    foreach ($horas_reservar as $id_horario) {
        $stmt_dicta->bind_param("iis", $ci_profesor, $id_horario, $dia_semana_seleccionado);
        $stmt_dicta->execute();
        $result = $stmt_dicta->get_result();

        if ($row = $result->fetch_assoc()) {
            $id_dicta = intval($row['id_dicta']);
            $id_curso = intval($row['id_curso']);
        } else {
            // No hay coincidencia → usar 0 en ambos campos
            $id_dicta = null;
            $id_curso = null;
        }

        $stmt_insert->bind_param("iiiiiss", $ci_profesor, $id_dicta, $id_curso, $id_espacio, $id_horario, $fecha_reservar, $dia_semana_seleccionado);

        if ($stmt_insert->execute()) {
            $horarios_insertados++;
        } else {
            // Si falla la inserción por restricción (FK, UNIQUE, etc.)
            $error = true;
            $mensaje_error = "Error al insertar reserva: No tienes clases a la hora que quieres reservar.";
            break;
        }
    }

    $stmt_dicta->close();
    $stmt_insert->close();

    // Confirmar o revertir según corresponda
    if ($error) {
        $con->rollback();
        $respuesta_json = [
            'estado' => 0,
            'mensaje' => $mensaje_error,
            'datos' => null
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
