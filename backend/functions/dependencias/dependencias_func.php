<?php
include_once('../../db/conexion.php');

$con = conectar_a_bd();

//Registra toda la dependencia
function registrar_dependencia_completa($con, $ci_profesor, $id_asignatura, $horarios, $id_espacio, $id_curso, $dia_dictado) {
    $respuesta_json = array();
    $error = false;
    $mensaje_error = "";
    
    // Iniciar transacción, las transacciones sirven para que, cuando hay que hacer muchas inserciones
    //anidadas, si alguna tira error, que se peudan borrar las inserciones anteriores y que no se queden ahi
    $con->begin_transaction();
    
    // PASO 1: VERIFICAR QUE LOS DATOS INGRESADOS EXISTAN! el usuario se puede hacer el loco, y con
    //inspeccionar le puede camviar el value a los option que son enviados por el formulario. Entonces 
    //hay que verificar que todas las cosas existan

    //Paso 1.a: Verificar que existan los profesores, se pasa como ? lo que ingreso el usuario
    //A lo largo de todo el codigo, antes de hacer algo se va a verificar que no hayan problemas (que error siga siendo false)
    if (!$error) {
        $query_verificar_prof = "SELECT ci_profesor FROM profesores WHERE ci_profesor = ?";
        $stmt = $con->prepare($query_verificar_prof);
        $stmt->bind_param("i", $ci_profesor);
        $stmt->execute();
        $result = $stmt->get_result();
        
        //Si no hay coincidencias, quiere decir que no existe el profesor ingresado, por lo que se marca
        //error y el mensaje de error, este funcionamiento tienen todas las validaciones de existencia
        if ($result->num_rows == 0) {
            $error = true;
            $mensaje_error = "El profesor no existe";
        }
        $stmt->close();
    }
    
    // Paso 1.b: Verificar que existan las asignaturas
    if (!$error) {
        $query_verificar_asig = "SELECT id_asignatura FROM asignaturas WHERE id_asignatura = ?";
        $stmt = $con->prepare($query_verificar_asig);
        $stmt->bind_param("i", $id_asignatura);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $error = true;
            $mensaje_error = "La asignatura no existe";
        }
        $stmt->close();
    }
    
    // Paso 1.c: Verificar que los espacios existan
    if (!$error) {
        $query_verificar_espacio = "SELECT id_espacio FROM espacios_fisicos WHERE id_espacio = ?";
        $stmt = $con->prepare($query_verificar_espacio);
        $stmt->bind_param("i", $id_espacio);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $error = true;
            $mensaje_error = "El espacio/ no existe";
        }
        $stmt->close();
    }
    
    // Paso 1.d: Verificar que el curso exista
    if (!$error) {
        $query_verificar_curso = "SELECT id_curso FROM cursos WHERE id_curso = ?";
        $stmt = $con->prepare($query_verificar_curso);
        $stmt->bind_param("i", $id_curso);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $error = true;
            $mensaje_error = "El curso no existe";
        }
        $stmt->close();
    }
    
    // PASO 2: Obtener ID_DICTA
    $id_dicta = null;
    
    if (!$error) {

        //Se declara la consulta SQL, los ? son los valores que EL USUARIO INGRESA
        $consulta_dicta = "SELECT id_dicta FROM profesor_dicta_asignatura WHERE id_asignatura = ? AND ci_profesor = ?";
        $stmt = $con->prepare($consulta_dicta);
        $stmt->bind_param("ii", $id_asignatura, $ci_profesor);//Se dice que, los dos datos son ints ('i' es para int, 's' para 
                                                              // string, por eso dice 'ii', ambos son int) y que los 
                                                              //simbolos de pregunta corresponden a id_asignatura y ci_profesor.

        $stmt->execute(); //Se ejecuta la consulta
        $result = $stmt->get_result(); //Se devuelve el resultado obtenido.
        
        //Trae los resultados como un array asociativo, si no esta vacio te devuelve la id_dicta
        $row = $result->fetch_assoc();
        if ($row) {
            // Ya existe, usar el id_dicta existente
            $id_dicta = $row['id_dicta'];
        } else {
            //Si no hay coincidencias, se hace una insercion para crear esta ID y se devuelve la misma
            //Pasa lo mismo que como cuando usas el SELECT, lo unico que se añade aca es el stmt execute 
            $sql_insert_dicta = "INSERT INTO profesor_dicta_asignatura (ci_profesor, id_asignatura) VALUES (?, ?)";
            $stmt_insert = $con->prepare($sql_insert_dicta);
            $stmt_insert->bind_param("ii", $ci_profesor, $id_asignatura);
            
            //Si se ejecuta todo bien, te devuelve la ID de dicta que se acabo de insertar
            if ($stmt_insert->execute()) {
                $id_dicta = $con->insert_id; //Se devuelve la ID de lo que recien se inserto
            }

            //Se cierran los statements y la conexion para evitar posibles errores a largo plazo,
            //Esto se va a hacer cada vez que se termine de insertar una cosa
            $stmt_insert->close();
        }
        $stmt->close();
    }
    
    // Paso 3: Verificar si el profesor no esta ocupado en los horarios que se ingresaron
    if (!$error) {
        $horarios_ocupados = array();
                                    //Los inner join son como haciamos el anio pasado con los WHERE; se encargan
                                    //de conectar dos o mas tablas que tengan algo en comun

                                    //tambien se le pone apodos a las tablas para que sea mas legible, por ejemplo:
                                    //c = tabla cumple; h = horarios; pda = profesor_dicta_asignatura

                                    //Este tipo de funconamiento va para todas las consultas que tengan INNER JOIN
        $query_verificar_horario = "SELECT h.id_horario, h.hora_inicio, h.hora_final FROM cumple c
                                    INNER JOIN profesor_dicta_asignatura pda ON c.id_dicta = pda.id_dicta
                                    INNER JOIN horarios h ON c.id_horario = h.id_horario
                                    WHERE pda.ci_profesor = ? AND h.id_horario = ? AND c.dia = ?";
        
        //Se prepara la consulta para verificar los horarios
        $stmt_verificar = $con->prepare($query_verificar_horario);
        
        //Para cada elemento del arreglo de horarios que existe, se verifica que el profesor no este ocupado
        foreach ($horarios as $id_horario) {
            $stmt_verificar->bind_param("iis", $ci_profesor, $id_horario, $dia_dictado);
            $stmt_verificar->execute();
            $resultado_verificar = $stmt_verificar->get_result();
            
            //Si hay resultados en esa consulta quiere decir que el profe esta ocupado a esa hora, por lo tanto
            //se guarda en el array de horarios_ocupados
            if ($resultado_verificar->num_rows > 0) {
                $horario_info = $resultado_verificar->fetch_assoc();
                $horarios_ocupados[] = $horario_info['hora_inicio'] . ' - ' . $horario_info['hora_final'];
            }
        }
        $stmt_verificar->close();
        
        //Si hay resulatdos, se detiene la ejecucion aca pq no se puede 
        //proseguir, te devuelve errores junto a un mensaje de error
        if (count($horarios_ocupados) > 0) {
            $error = true;
                                                                                //Implode separa al array con comas
            $mensaje_error = "El profesor en el dia " . $dia_dictado . " ya tiene clases en estos horarios: " . implode(", ", $horarios_ocupados);
        }
    }
    
    // Paso 4: Verificar que el salon no este ocupado a la hora que se ingreso
    if (!$error) {
        $horarios_salon_ocupado = array();
                                //Se juntan las tablas de: cumple, dicta_ocupa_espacio, horarios para ver si
                                //algun profe DICTA una asignatura en ese salon a una hora dada
        $query_verificar_salon = "SELECT h.id_horario, h.hora_inicio, h.hora_final FROM cumple c
                                  INNER JOIN dicta_ocupa_espacio doe ON c.id_dicta = doe.id_dicta
                                  INNER JOIN horarios h ON c.id_horario = h.id_horario
                                  WHERE doe.id_espacio = ? AND h.id_horario = ? AND c.dia = ?";
        
        //Se prepara la consulta
        $stmt_verificar_salon = $con->prepare($query_verificar_salon);
        
        //Se van recorriendo los horarios, para ver si en algunos de esos el salon esta ocupado
        foreach ($horarios as $id_horario) {
            $stmt_verificar_salon->bind_param("iis", $id_espacio, $id_horario, $dia_dictado);
            $stmt_verificar_salon->execute();
            $resultado_verificar_salon = $stmt_verificar_salon->get_result();
            
            if ($resultado_verificar_salon->num_rows > 0) {
                $horario_info = $resultado_verificar_salon->fetch_assoc();
                $horarios_salon_ocupado[] = $horario_info['hora_inicio'] . ' - ' . $horario_info['hora_final'];
            }
        }
        $stmt_verificar_salon->close();
        
        if (count($horarios_salon_ocupado) > 0) {
            $error = true;
            $mensaje_error = "El salón en el dia " . $dia_dictado . " ya está ocupado en estos horarios: " . implode(", ", $horarios_salon_ocupado);
        }
    }

    //Paso 4.5: Verificar que el curso no este ocupado a la hora que se quiere dictar
if (!$error) {
    $horarios_curso_ocupado = array();

    $query_verificar_curso = "SELECT h.id_horario, h.hora_inicio, h.hora_final FROM cumple c
                            INNER JOIN dicta_en_curso denc ON c.id_dicta = denc.id_dicta
                            INNER JOIN horarios h ON c.id_horario = h.id_horario
                            WHERE denc.id_curso = ? AND h.id_horario = ? AND c.dia = ?";

    $stmt_verificar_curso = $con->prepare($query_verificar_curso);


    foreach ($horarios as $id_horario) {
        $stmt_verificar_curso->bind_param("iis", $id_curso, $id_horario, $dia_dictado);
        $stmt_verificar_curso->execute();
        $resultado_verificar_curso = $stmt_verificar_curso->get_result();
        
        if ($resultado_verificar_curso->num_rows > 0) {
            $horario_info = $resultado_verificar_curso->fetch_assoc();
            $horarios_curso_ocupado[] = $horario_info['hora_inicio'] . ' - ' . $horario_info['hora_final'];
        }
    }
    $stmt_verificar_curso->close();
    
    if (count($horarios_curso_ocupado) > 0) {
        $error = true;
        $mensaje_error = "El curso en el dia " . $dia_dictado . " ya está ocupado en estos horarios: " . implode(", ", $horarios_curso_ocupado);
    }
}
    
    //  PASO 5: Insertar los horarios en tabla cumple (m estoy cansando de comentar)
    $horarios_insertados = 0;
    $asistencia = 1;

    if (!$error) {
        $query_insert_cumple = "INSERT INTO cumple (id_horario, id_dicta, dia, asistencia) VALUES (?, ?, ?, ?)";
        $stmt_cumple = $con->prepare($query_insert_cumple);
        
        //Para cada uno de los horarios que hay, se van metiendo registros en la BD
        foreach ($horarios as $id_horario) {
            $stmt_cumple->bind_param("iisi", $id_horario, $id_dicta, $dia_dictado, $asistencia);
            
            if ($stmt_cumple->execute()) {
                $horarios_insertados++;

            //Si llega a haber error, se marca como error y se detiene todo
            } else {
                $error = true;
                $mensaje_error = "Error al insertar horarios";
                break;
            }
        }
        $stmt_cumple->close();
    }
    // PASO 6: Insertar en dicta_ocupa_espacio
    if (!$error) {
        //Puede ocurrir que ya se haya registrado que cierto profesor da clases en cierto salon,
        //Por lo que hay que verificar si existe. Si no existe se inserta
        $query_verificar_espacio_dicta = "SELECT * FROM dicta_ocupa_espacio WHERE id_dicta = ? AND id_espacio = ?";
        $stmt_ver = $con->prepare($query_verificar_espacio_dicta);
        $stmt_ver->bind_param("ii", $id_dicta, $id_espacio);
        $stmt_ver->execute();
        $result_ver = $stmt_ver->get_result();
        
        //Si no hay coincidencias, se inserta. En el caso de que ya exista, simplemente continua (no lo vuelve a crear)
        if ($result_ver->num_rows == 0) {
            // No existe, insertar
            $query_insertar_espacio = "INSERT INTO dicta_ocupa_espacio (id_dicta, id_espacio) 
                                      VALUES (?, ?)";
            $stmt_espacio = $con->prepare($query_insertar_espacio);
            $stmt_espacio->bind_param("ii", $id_dicta, $id_espacio);
            
            if (!$stmt_espacio->execute()) {
                $error = true;
                $mensaje_error = "Error al asignar el espacio";
            }
            $stmt_espacio->close();
        }
        $stmt_ver->close();
    }
    
    // PASO 7: Insertar en dicta_en_curso
    if (!$error) {
        //Mismo funcionamiento que el paso 6
        $query_verificar_curso_dicta = "SELECT * FROM dicta_en_curso 
                                        WHERE id_dicta = ? AND id_curso = ?";
        $stmt_ver_curso = $con->prepare($query_verificar_curso_dicta);
        $stmt_ver_curso->bind_param("ii", $id_dicta, $id_curso);
        $stmt_ver_curso->execute();
        $result_ver_curso = $stmt_ver_curso->get_result();
        
        if ($result_ver_curso->num_rows == 0) {
            // No existe, insertar
            $query_insertar_curso = "INSERT INTO dicta_en_curso (id_dicta, id_curso) 
                                    VALUES (?, ?)";
            $stmt_curso = $con->prepare($query_insertar_curso);
            $stmt_curso->bind_param("ii", $id_dicta, $id_curso);
            
            if (!$stmt_curso->execute()) {
                $error = true;
                $mensaje_error = "Error al asignar el curso";
            }
            $stmt_curso->close();
        }
        $stmt_ver_curso->close();
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
        $respuesta_json['mensaje'] = "Dependencia registrada correctamente. Se asignaron {$horarios_insertados} horarios.";
        $respuesta_json['datos'] = array(
            'id_dicta' => $id_dicta,
            'horarios_insertados' => $horarios_insertados
        );
    }
    
    return $respuesta_json;
}

?>