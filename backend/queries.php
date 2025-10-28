<?php
include_once("db/conexion.php");

function query_espaciosfisicos($con){
    $sql = "SELECT * FROM espacios_fisicos";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();    
}


function query_espacios_sin_general($con){
    $sql = "SELECT * FROM espacios_fisicos WHERE nombre != 'general'";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function query_espacios_por_dia($con, $dia, $espaciossql){
    $sql = "SELECT DISTINCT
            a.nombre AS nombre_asignatura,
            e.nombre AS nombre_espacio,
            h.hora_inicio,
            h.hora_final,
            h.id_horario,
            h.tipo,
            c.nombre AS nombre_curso,
            cu.dia,
            pda.ci_profesor,
            CONCAT (p.nombre, ' ', p.apellido) as nombre_profesor
        FROM cumple cu
        INNER JOIN profesor_dicta_asignatura pda ON cu.id_dicta = pda.id_dicta
        INNER JOIN asignaturas a ON pda.id_asignatura = a.id_asignatura
        INNER JOIN dicta_en_curso dc ON pda.id_dicta = dc.id_dicta
        INNER JOIN cursos c ON dc.id_curso = c.id_curso
        INNER JOIN horarios h ON cu.id_horario = h.id_horario
        INNER JOIN dicta_ocupa_espacio doe ON cu.id_dicta = doe.id_dicta 
        INNER JOIN profesores p ON p.ci_profesor = pda.ci_profesor
            AND cu.id_horario = doe.id_horario 
            AND cu.dia = doe.dia
        INNER JOIN espacios_fisicos e ON doe.id_espacio = e.id_espacio
        WHERE cu.dia = ?
            AND e.id_espacio = ?
            AND h.tipo = 'clase'
        ORDER BY h.hora_inicio ASC";

    $stmt_espacios = $con->prepare($sql);
    $stmt_espacios->bind_param("si", $dia, $espaciossql);
    $stmt_espacios->execute();

    return $stmt_espacios->get_result();
}

function query_profesores($con){
    $query_profesores = "SELECT * FROM profesores";
    $stmt = $con->prepare($query_profesores);
    $stmt->execute();
    return $stmt->get_result();
}

function query_profesor_especifico($con, $ci_profesor){
    $query_verificar_prof = "SELECT ci_profesor FROM profesores WHERE ci_profesor = ?";
    $stmt = $con->prepare($query_verificar_prof);
    $stmt->bind_param("i", $ci_profesor);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
    return $resultado;
}

function query_horarios_profe_pordia($con, $dia, $professql){
    $sql = "SELECT distinct
        a.nombre AS nombre_asignatura,
        e.nombre AS nombre_espacio,
        h.hora_inicio, 
        e.id_espacio,
        h.hora_final,
        h.id_horario,
        c.nombre AS nombre_curso,
        cu.dia,
        p.ci_profesor
    FROM cumple cu
    INNER JOIN profesor_dicta_asignatura pda ON cu.id_dicta = pda.id_dicta
    INNER JOIN asignaturas a ON pda.id_asignatura = a.id_asignatura
    INNER JOIN dicta_en_curso dc ON pda.id_dicta = dc.id_dicta
    INNER JOIN cursos c ON dc.id_curso = c.id_curso
    INNER JOIN horarios h ON cu.id_horario = h.id_horario
    INNER JOIN dicta_ocupa_espacio doe ON cu.id_dicta = doe.id_dicta 
        AND cu.id_horario = doe.id_horario 
        AND cu.dia = doe.dia
    INNER JOIN espacios_fisicos e ON doe.id_espacio = e.id_espacio
    INNER JOIN profesores p ON p.ci_profesor = pda.ci_profesor
    WHERE cu.dia = ?
        AND p.ci_profesor = ?
    ORDER BY h.hora_inicio ASC";

    $stmt_profe = $con->prepare($sql);
    $stmt_profe->bind_param("si", $dia, $professql);
    $stmt_profe->execute();
    return $stmt_profe->get_result();
}

function query_asignaturas($con){
    $query_asignaturas = "SELECT * FROM asignaturas";
    $stmt = $con->prepare($query_asignaturas);
    $stmt->execute();
    return $stmt->get_result();
}

function query_horarios($con){
    $query_horarios = "SELECT * FROM horarios WHERE tipo = 'clase'";
    $stmt = $con->prepare($query_horarios);
    $stmt->execute();
    return $stmt->get_result();
}

function query_cursos($con){
    $query_cursos = "SELECT * FROM cursos";
    $stmt = $con->prepare($query_cursos);
    $stmt->execute();
    return $stmt->get_result();
}

function query_horas_curso($con, $cursosql){
    $sql = "SELECT DISTINCT
                h.id_horario,
                h.hora_inicio,
                h.hora_final,
                CONCAT (p.nombre, ' ', p.apellido) as nombre_profesor
            FROM cumple cu
            INNER JOIN profesor_dicta_asignatura pda ON cu.id_dicta = pda.id_dicta
            INNER JOIN dicta_en_curso dc ON pda.id_dicta = dc.id_dicta
            INNER JOIN cursos c ON dc.id_curso = c.id_curso
            INNER JOIN horarios h ON cu.id_horario = h.id_horario
            INNER JOIN profesores p ON p.ci_profesor = pda.ci_profesor
            WHERE c.id_curso = ?
                AND h.tipo = 'clase'
            ORDER BY h.hora_inicio ASC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $cursosql);
    $stmt->execute();
    return $stmt->get_result();
}

function query_horas_dia_curso($con, $dia, $cursosql){
    $sql = "SELECT 
        a.nombre AS nombre_asignatura,
        e.nombre AS nombre_espacio,
        e.id_espacio,
        h.hora_inicio,
        h.hora_final,
        h.id_horario,
        h.tipo,
        c.nombre AS nombre_curso,
        cu.dia,
        pda.ci_profesor,
        CONCAT (p.nombre, ' ', p.apellido) as nombre_profesor
    FROM cumple cu
    INNER JOIN profesor_dicta_asignatura pda ON cu.id_dicta = pda.id_dicta
    INNER JOIN asignaturas a ON pda.id_asignatura = a.id_asignatura
    INNER JOIN dicta_en_curso dc ON pda.id_dicta = dc.id_dicta 
        AND cu.id_horario = dc.id_horario 
        AND cu.dia = dc.dia
    INNER JOIN cursos c ON dc.id_curso = c.id_curso
    INNER JOIN horarios h ON cu.id_horario = h.id_horario
    INNER JOIN profesores p ON p.ci_profesor = pda.ci_profesor
    INNER JOIN dicta_ocupa_espacio doe ON cu.id_dicta = doe.id_dicta 
        AND cu.id_horario = doe.id_horario 
        AND cu.dia = doe.dia
    INNER JOIN espacios_fisicos e ON doe.id_espacio = e.id_espacio
    WHERE cu.dia = ?
        AND c.id_curso = ?
        AND h.tipo = 'clase'
    ORDER BY h.hora_inicio ASC";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $dia, $cursosql);
    $stmt->execute();
    return $stmt->get_result();
}


function query_orientacion($con){
    $query_orientacion = "SELECT * FROM orientacion";
    $stmt = $con->prepare($query_orientacion);
    $stmt->execute();
    return $stmt->get_result();
}

function query_recursos($con){
    $query_recursos = "SELECT * FROM recursos WHERE estado != 'uso'";
    $stmt = $con->prepare($query_recursos);
    $stmt->execute();
    return $stmt->get_result();
}

function query_prestamos($con){
    // Consulta SQL para obtener la información de préstamos
    $query_unida = "SELECT 
    -- SAR es el apodo de la tabla su_administra_recursos, 
    -- le dice que traiga id_solicita, hora presta y vuelta de esa tabla
        sar.id_solicita, sar.hora_presta, sar.hora_vuelta, 

    -- p es el apodo de profesores.
    -- trae datos con apodos (por ejemplo, nombre 
    -- lo trae como nombre_profesor)
        p.nombre AS nombre_profesor, p.apellido AS apellido_profesor, p.ci_profesor, 
    -- r = apodo de tabla RECURSOS
        r.nombre AS nombre_recurso, r.id_recurso, r.estado AS estado_recurso,

    -- su = apodo de tabla SUPERUSUARIO
        su.nombre AS nombre_su, su.apellido AS apellido_su, su.id_superusuario

    -- Junta tablas que tienen datos en comun, diciendo que el origen es su_administra_usuarios y tiene apodo de 'sar'
    -- Nosotros esto lo haciamos con WHERE el anio pasado, pero queda mas legible con INNER JOIN
        FROM su_administra_recursos sar
        INNER JOIN profesor_solicita_recurso psr ON sar.id_solicita = psr.id_solicita
        INNER JOIN profesores p ON psr.ci_profesor = p.ci_profesor
        INNER JOIN recursos r ON psr.id_recurso = r.id_recurso
        INNER JOIN superUsuario su ON sar.id_superusuario = su.id_superusuario

    -- Se ordena como descendiente para que los mas nuevos aparezcan primero
        ORDER BY sar.hora_presta DESC";

    //Se ejecuta la query y se trae el resultado
    $stmt = $con->prepare($query_unida);
    $stmt->execute();
    return $stmt->get_result();
}

function query_prestamos_profesores($con, $ci_profesor){
    $query = "SELECT 
    r.nombre as nombre_recurso,
    sar.hora_presta,
    sar.hora_vuelta
    FROM profesor_solicita_recurso psr
    INNER JOIN recursos r ON psr.id_recurso = r.id_recurso
    INNER JOIN su_administra_recursos sar ON psr.id_solicita = sar.id_solicita
    WHERE psr.ci_profesor = ? AND sar.hora_vuelta is null
    ORDER BY sar.hora_presta DESC";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $ci_profesor);
    $stmt->execute();
    return $stmt->get_result();
}

//Devuelve todas las inasistencias que hay en esta semana
function query_inasistencias_esta_semana($con, $inicio_semana, $fin_semana){
    $query_inasistencias = "SELECT 
    i.fecha_inasistencia, i.ci_profesor, i.id_horario,
    h.hora_inicio, h.hora_final
    FROM inasistencia i
    INNER JOIN horarios h ON i.id_horario = h.id_horario
    WHERE i.fecha_inasistencia BETWEEN ? AND ?";

    $stmt_inasistencias = $con->prepare($query_inasistencias);
    $stmt_inasistencias->bind_param("ss", $inicio_semana, $fin_semana);
    $stmt_inasistencias->execute();
    return $stmt_inasistencias->get_result();
}


//Retorna toda la informacion de las reservas en una semana especifica (para mostrar en el calendario)
function query_reservas_semana($con, $inicio_semana, $fin_semana) {
    $sql = "SELECT 
        r.id_reserva, r.fecha_reserva, r.dia, r.id_espacio, r.id_horario, r.ci_profesor, r.id_dicta, r.id_curso,
        a.nombre AS nombre_asignatura,
        e.nombre AS nombre_espacio,
        h.hora_inicio, h.hora_final,
        c.nombre AS nombre_curso,
        CONCAT(p.nombre, ' ', p.apellido) AS nombre_profesor
    FROM reservas_espacios r
    INNER JOIN profesor_dicta_asignatura pda ON r.id_dicta = pda.id_dicta
    INNER JOIN asignaturas a ON pda.id_asignatura = a.id_asignatura
    INNER JOIN cursos c ON r.id_curso = c.id_curso
    INNER JOIN horarios h ON r.id_horario = h.id_horario
    INNER JOIN espacios_fisicos e ON r.id_espacio = e.id_espacio
    INNER JOIN profesores p ON r.ci_profesor = p.ci_profesor
    WHERE r.fecha_reserva BETWEEN ? AND ?
    ORDER BY r.fecha_reserva, h.hora_inicio ASC";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $inicio_semana, $fin_semana);
    $stmt->execute();
    return $stmt->get_result();
}


//FUNCIONES VARIADAS
function saber_dia_seleccionado($dia_semana_seleccionado) {
    $dias = array(
        0 => 'lunes',
        1 => 'martes',
        2 => 'miercoles',
        3 => 'jueves',
        4 => 'viernes'
    );

    $devuelto = isset($dias[$dia_semana_seleccionado]) ? $dias[$dia_semana_seleccionado] : null;
    return $devuelto;
}


//Funcion de mostrar datos
function mostrardatos($buscar) {
    $connect = conectar_a_bd();
    $sql = "SELECT * FROM $buscar";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_execute($stmt);
    return $result = mysqli_stmt_get_result($stmt);
}

//Function inasistencias mostrar datos
function inasistenciasMostrar($ci) {
    $connect = conectar_a_bd();

    $sql = "SELECT i.*, h.hora_inicio, h.hora_final, h.tipo
        FROM inasistencia i
        INNER JOIN horarios h ON i.id_horario = h.id_horario
        WHERE i.ci_profesor = ?";

    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $ci);
    mysqli_stmt_execute($stmt);
    return $result = mysqli_stmt_get_result($stmt);

}