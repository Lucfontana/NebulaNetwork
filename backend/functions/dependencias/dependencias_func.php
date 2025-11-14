<?php
include_once('../../db/conexion.php');
include_once('../../queries.php'); //contiene funciones SQL ya preparadas
include_once('../../helpers.php'); //contiene funciones de ayuda

$con = conectar_a_bd();

//Registra toda la dependencia
function registrar_dependencia_completa($con, $ci_profesor, $id_asignatura, $horarios, $id_espacio, $id_curso, $dia_dictado) {
    $respuesta_json = array(); //Este array se usará al final de la función para devolver la respuesta al frontend
    $error = false; // si el error no existe, se ejecuta el bloque
    $mensaje_error = ""; //Guarda el texto explicativo del error, en caso de que ocurra alguno.
    
    // Iniciar transacción, las transacciones sirven para que, cuando hay que hacer muchas inserciones
    //anidadas, si alguna tira error, que se peudan borrar las inserciones anteriores y que no se queden ahi
    $con->begin_transaction();
    
    // PASO 1: VERIFICAR QUE LOS DATOS INGRESADOS EXISTAN! el usuario se puede hacer el loco, y con
    //inspeccionar le puede cambiar el value a los option que son enviados por el formulario. Entonces 
    //hay que verificar que todas las cosas existan

    //Paso 1.a: Verificar que existan los profesores, se pasa como ? lo que ingreso el usuario
    //A lo largo de todo el codigo, antes de hacer algo se va a verificar que no hayan problemas (que error siga siendo false)
    if (!$error) {

        //query que proviene de queries.php
        $result = query_profesor_especifico($con, $ci_profesor);
        
        //Si no hay coincidencias, quiere decir que no existe el profesor ingresado, por lo que se marca
        //error y el mensaje de error, este funcionamiento tienen todas las validaciones de existencia
        if ($result->num_rows == 0) {
            $error = true;
            $mensaje_error = "El profesor no existe";
        }
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

    //Paso 1.5 Se verifica que un horario no se haya repetido dos veces
    if (!$error) {
                                //funcion de helpers.php
        $horarios_duplicados = horarios_duplicados($horarios);        
        // Si hay al menos un horario duplicado en el array
        if (count($horarios_duplicados) > 0) {
            // Marcamos que hay error para detener el proceso
            $error = true;
            
            // Creamos un mensaje de error personalizado
            $mensaje_error = "No puedes seleccionar el mismo horario más de una vez";
        }
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
    
        //Paso 4: Verificar que el salon no este ocupado a la hora que se ingreso
    if (!$error) {
        $horarios_salon_ocupado = array();
        
                                //Se junta la tabla dicta_ocupa_espacio con los horarios
                                //Para verificar si el espacio fisico esta ocupado a una hora dada                        
        $query_verificar_salon = "SELECT h.id_horario, h.hora_inicio, h.hora_final 
                                  FROM dicta_ocupa_espacio doe
                                  INNER JOIN horarios h ON doe.id_horario = h.id_horario
                                  WHERE doe.id_espacio = ? 
                                    AND doe.id_horario = ? 
                                    AND doe.dia = ?";
        
        $stmt_verificar_salon = $con->prepare($query_verificar_salon);
        
        //Se van recorriendo los horarios, para ver si en algunos de esos el salon esta ocupado.
        //el bloque se ejecuta una vez por cada horario que el usuario quiere registrar.
        foreach ($horarios as $id_horario) {
            $stmt_verificar_salon->bind_param("iis", $id_espacio, $id_horario, $dia_dictado);
            $stmt_verificar_salon->execute();
            $resultado_verificar_salon = $stmt_verificar_salon->get_result();
            
            if ($resultado_verificar_salon->num_rows > 0) { //si hay alguna fila que coincida, significa que el salón ya está ocupado en ese horario y día
                $horario_info = $resultado_verificar_salon->fetch_assoc(); //fetch_assoc() obtiene los datos de esa fila en forma de array asociativo
                $horarios_salon_ocupado[] = $horario_info['hora_inicio'] . ' - ' . $horario_info['hora_final']; //Luego concatena esas horas en un string y lo agrega al arreglo $horarios_salon_ocupado[].
            }
        }
        $stmt_verificar_salon->close();
        
        if (count($horarios_salon_ocupado) > 0) { //Verifica si se detectaron horarios ocupados
            $error = true;
            $mensaje_error = "El salón en el dia " . $dia_dictado . " ya está ocupado en estos horarios: " . implode(", ", $horarios_salon_ocupado);
        }
    }

    //Paso 4.5: Verificar que el curso no este ocupado a la hora que se quiere dictar
if (!$error) {
    $horarios_curso_ocupado = array();

    // CAMBIO: Verificar con id_horario y dia específicos
    $query_verificar_curso = "SELECT h.id_horario, h.hora_inicio, h.hora_final 
                             FROM dicta_en_curso denc
                             INNER JOIN horarios h ON denc.id_horario = h.id_horario
                             WHERE denc.id_curso = ? 
                                AND denc.id_horario = ? 
                                AND denc.dia = ?";

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
    
    //  PASO 5: Insertar los horarios en tabla cumple
    $horarios_insertados = 0;

    if (!$error) { // esto significa:“Si hasta ahora no se ha producido ningún error, entonces hacemos esta
                   // parte.” "si error es false"
        $query_insert_cumple = "INSERT INTO cumple (id_horario, id_dicta, dia) VALUES (?, ?, ?)";
        $stmt_cumple = $con->prepare($query_insert_cumple);
        
        //Para cada uno de los horarios que hay, se van metiendo registros en la BD
        foreach ($horarios as $id_horario) {
            $stmt_cumple->bind_param("iis", $id_horario, $id_dicta, $dia_dictado);
            
            if ($stmt_cumple->execute()) { //Si la inserción fue exitosa se ejecuta el INSERT en la base de datos.
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
        $query_insertar_espacio = "INSERT INTO dicta_ocupa_espacio (id_dicta, id_horario, dia, id_espacio) 
                                  VALUES (?, ?, ?, ?)";
        $stmt_espacio = $con->prepare($query_insertar_espacio);
        
        foreach ($horarios as $id_horario) { //recorre el array de horarios seleccionados por el usuario
           
            // Verificar si ya existe esta combinación específica para no duplicar datos
            $query_verificar = "SELECT * FROM dicta_ocupa_espacio 
                               WHERE id_dicta = ? AND id_horario = ? AND dia = ? AND id_espacio = ?";
            $stmt_ver = $con->prepare($query_verificar);
            $stmt_ver->bind_param("iisi", $id_dicta, $id_horario, $dia_dictado, $id_espacio);
            $stmt_ver->execute();
            $result_ver = $stmt_ver->get_result();
            
            if ($result_ver->num_rows == 0) { //Si no existe ya una clase con esos mismos datos, inserta una nueva
                $stmt_espacio->bind_param("iisi", $id_dicta, $id_horario, $dia_dictado, $id_espacio);
                
                if (!$stmt_espacio->execute()) { //Si execute() falla, se marca $error = true y se interrumpe el ciclo (break)
                    $error = true;
                    $mensaje_error = "Error al asignar el espacio para el horario";
                    break;
                }
            }
            $stmt_ver->close();
        }
        $stmt_espacio->close();
    }


    // PASO 7: Insertar en dicta_en_curso

if (!$error) { //Este bloque se ejecuta solo si no hubo errores anteriores, es decir si: 
// el profesor existe,la asignatura existe,el salón no está ocupado, etc.

    $query_insertar_curso = "INSERT INTO dicta_en_curso (id_dicta, id_curso, id_horario, dia) 
                            VALUES (?, ?, ?, ?)";
    $stmt_curso = $con->prepare($query_insertar_curso);
    
    foreach ($horarios as $id_horario) {//Se repite una acción por cada horario seleccionado

        // Verificar si ya existe esta combinación específica
        $query_verificar = "SELECT * FROM dicta_en_curso 
                           WHERE id_dicta = ? AND id_curso = ? AND id_horario = ? AND dia = ?";
        $stmt_ver_curso = $con->prepare($query_verificar);
        $stmt_ver_curso->bind_param("iiis", $id_dicta, $id_curso, $id_horario, $dia_dictado);
        $stmt_ver_curso->execute();
        $result_ver_curso = $stmt_ver_curso->get_result();
        
        if ($result_ver_curso->num_rows == 0) { //Si no existe todavía una relación entre ese profesor, 
                                               // curso, horario y día, entonces se inserta en la tabla.
            $stmt_curso->bind_param("iiis", $id_dicta, $id_curso, $id_horario, $dia_dictado);
            
            if (!$stmt_curso->execute()) { //Si ocurre algún error al hacerlo, marcá $error = true y sale 
                                           // del bucle.
                $error = true;
                $mensaje_error = "Error al asignar el curso para el horario";
                break;
            }
        }
        $stmt_ver_curso->close();
    }
    $stmt_curso->close();
}
    
    // ========== PARTE IMPORTANTE!!!! ==========
    //Revertir cambios: A lo largo de toda la funcion fuimos insertando cosas en la base de datos, pero si
    //una cosa llegara a salir mal, esas inserciones ya se quedarian ahi. Por lo tanto, el manejo de errores 
    //con lo de las transacciones que se explico al principio es importante aca.

    //Si llega a haber error, se puede hacer rollback. Esto quiere decir que esas inserciones se van a borrar
    //asi no quedan hechas porque si.
    if ($error) {
        // Hubo algún error, revertir todo
        $con->rollback();
        
        $respuesta_json['estado'] = 0; //0 = error
        $respuesta_json['mensaje'] = $mensaje_error; //Guarda dentro de la respuesta el mensaje de error que se generó antes.
        $respuesta_json['datos'] = null; //Esto indica que no hay datos que devolver, ya que el proceso falló.
        
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

function eliminar_dependencia($con, $curso, $horario, $dia){
    $con->begin_transaction();

    $respuesta_json = array();
    $error = false;
    $mensaje_error = "";

    //Esta consulta busca si existe una clase con ese curso, horario y día
    $query_comprobar = "SELECT * FROM dicta_en_curso WHERE id_curso = ? AND id_horario = ? AND dia = ?";

    //Se comprueba que la clase exista
    if (!$error){
        $stmt = $con->prepare($query_comprobar);
        $stmt->bind_param("iis", $curso, $horario, $dia);
        $stmt->execute();
 
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 0) { //Si no existe, se marca error
            $error = true;
            $mensaje_error = "No existe una clase con los datos elegidos, vuelva a intentarlo";
            $datos = "Curso: " . $curso . " Id horario: " . $horario . " Dia: " . $dia; 
       
        } else {//Si sí existe, guarda el id_dicta (la relación del profesor con la materia):
            $fila = $resultado->fetch_assoc();
            $id_dicta = $fila['id_dicta'];
        }
        $stmt->close(); 
    }


    if (!$error){
        //Se declaran las dos queries para eliminar las clases
        $query_liberar_curso = "DELETE FROM dicta_en_curso WHERE id_curso = ? AND id_horario = ? AND dia = ?";
        $query_liberar_espacio = "DELETE FROM dicta_ocupa_espacio WHERE id_dicta = ? AND id_horario = ? AND dia = ?";
       
        // $query_borrar_inasistencia ="DELETE FROM inasistencias i INNER JOIN profesor_dicta_asignatura pda WHERE i.ci_profesor = pda.ci_profesor";
        $query_liberar_profe ="DELETE FROM cumple WHERE id_dicta = ? AND id_horario = ? AND dia = ?";
        $query_liberar_reserva ="DELETE FROM reservas_espacios WHERE id_dicta = ? AND id_horario = ? AND dia = ? AND id_curso = ?";

        // luego se ejecutan las queries una por una, se repite para cada tabla relacionada con esa clase.
        $stmt = $con->prepare($query_liberar_curso);
        $stmt->bind_param("iis", $curso, $horario, $dia);
        $resultado1 = $stmt->execute();  //Ejecutar y guardar
        $stmt->close();

        $stmt2 = $con->prepare($query_liberar_espacio);
        $stmt2->bind_param("iis", $id_dicta, $horario, $dia);
        $resultado2 = $stmt2->execute();  //Ejecutar y guardar
        $stmt2->close();

        $stmt3 = $con->prepare($query_liberar_profe);
        $stmt3->bind_param("iis", $id_dicta, $horario, $dia);
        $resultado3 = $stmt3->execute();
        $stmt3->close();

        $stmt4 = $con->prepare($query_liberar_reserva);
        $stmt4->bind_param("iisi", $id_dicta, $horario, $dia, $curso);
        $resultado4 = $stmt4->execute();
        $stmt4->close();

        //Verifica que todos los DELETE funcionaron
        if (!$resultado1 || !$resultado2 ||!$resultado3 ||!$resultado4){  //Verificar si ambas queries se ejecutaron
            $error = true; //Si alguna de las consultas falló, marca error.
            $mensaje_error = "Error al eliminar los horarios, vuelva a intentarlo";
        }
    }

    if ($error) {
        // Hubo algún error, revertir todo
        $con->rollback();
        
        $respuesta_json['estado'] = 0;
        $respuesta_json['mensaje'] = $mensaje_error;
        $respuesta_json['datos'] = $datos;
        
    } else {
        // todo bien, confirmar cambios
        $con->commit();
        
        $respuesta_json['estado'] = 1;
        $respuesta_json['mensaje'] = "Clase eliminada correctamente.";
    }
        return $respuesta_json;
}


?>