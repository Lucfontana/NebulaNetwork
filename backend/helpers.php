<?php

function cargar_horarios($query, $dias, $materias_por_dia, $dato_mostrar, $dato_adicional)
{
?>
    <?php mysqli_data_seek($query, 0); ?>
    <div class="datos-body">
        <?php while ($row = mysqli_fetch_array($query)): ?> <!-- Para cada horario, se muestra la hora de inicio y la hora final -->
            <div class="datos-row mostrar-datos">
                <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                <?php foreach ($dias as $dia): ?> <!--Por cada dia se repite la busqueda-->
                    <?php
                    $mostrar = false;
                    
                    // 1: Verificar si hay RESERVA en este horario
                    foreach ($materias_por_dia[$dia] as $m) { //Para cada materia en un dia especifico
                        // Si la reserva a una hora especifica esta marcada como true, se muestra el horario con la clase
                        //(Si la query de horas a la que esta ocupada un curso ($row) es igual a un horario donde hay reserva )
                        if (isset($m['es_reserva']) && $m['es_reserva'] === true &&
                            $m['hora_inicio'] == $row['hora_inicio'] && 
                            $m['hora_final'] == $row['hora_final'] &&
                            $m['id_horario'] == $row['id_horario']) {
                            
                            $clase_inasistencia = isset($m['tiene_inasistencia']) && $m['tiene_inasistencia'] ? 'inasistencia-marcada' : '';
                            
                            //Si tiene inasistencia y reserva, le da prioridad a la inasistencia mostrando las dos clases 
                            echo "<div class='dia-dato reserva-clase {$clase_inasistencia}'>
                                <strong>{$m[$dato_mostrar]}</strong>
                                <small>{$m[$dato_adicional]}</small>
                                </div>";
                            $mostrar = true;
                            break;
                        }
                    }
                    
                    // PRIORIDAD 2: Si no hay reserva, mostrar clase regular
                    if (!$mostrar) {
                        foreach ($materias_por_dia[$dia] as $m) {
                            if ((!isset($m['es_reserva']) || $m['es_reserva'] === false) &&
                                $m['hora_inicio'] == $row['hora_inicio'] && 
                                $m['hora_final'] == $row['hora_final'] &&
                                $m['id_horario'] == $row['id_horario']) {
                                
                                $clase_inasistencia = isset($m['tiene_inasistencia']) && $m['tiene_inasistencia'] ? 'inasistencia-marcada' : '';
                                echo "<div class='dia-dato {$clase_inasistencia}'>
                                    <strong>{$m[$dato_mostrar]}</strong>
                                    <small>{$m[$dato_adicional]}</small>
                                    </div>";
                                $mostrar = true;
                                break;
                            }
                        }
                    }
                    
                    if (!$mostrar) {
                        echo "<div class='dia-dato'><em>---</em></div>";
                    }
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php
}
function cabecera_horarios()
{
?>
    <div class="datos-header">
        <div class="datos-row">
            <div class="horas-titulo"><?= t("label_hours") ?></div>
            <div class="dias"><?= t("day_monday") ?></div>
            <div class="dias"><?= t("day_tuesday") ?></div>
            <div class="dias"><?= t("day_wednesday") ?></div>
            <div class="dias"><?= t("day_thursday") ?></div>
            <div class="dias"><?= t("day_friday") ?></div>
        </div>
    </div>
<?php
}

function cabecera_horarios_celular()
{
?>
    <div class="datos-header">
        <div class="datos-row">
            <div class="horas-titulo"><?= t("label_hours") ?></div>
            <select class="dias" id="select-dias">
                <option value="lunes" class="dias"><?= t("day_monday") ?></option>
                <option value="martes" class="dias"><?= t("day_tuesday") ?></option>
                <option value="miercoles" class="dias"><?= t("day_wednesday") ?></option>
                <option value="jueves" class="dias"><?= t("day_thursday") ?></option>
                <option value="viernes" class="dias"><?= t("day_friday") ?></option>
            </select>
        </div>
    </div>
<?php
}

function boton_eliminar($id_contenedor, $texto, $id_confirmar, $id_cancelar)
{
?>
    <div class="overlay" id="<?= $id_contenedor; ?>">
        <div class="confirmacion">
            <h2>¿Estás seguro?</h2>
            <p>Esta acción eliminará <?= htmlspecialchars($texto); ?> de forma permanente.</p>
            <div class="botones_confirmar">
                <button class="btn-confirmar" id="<?= $id_confirmar; ?>">Eliminar</button>
                <button class="btn-cancelar" id="<?= $id_cancelar; ?>">Cancelar</button>
            </div>
        </div>
    </div>
<?php
}

function toggle_mostrar_info($nombre)
{
?>
    <div class="datos-tabla-flex">
        <div class="nombre-titulo grid-cell flex-header mostrar-informacion-oculta">
            <?= $nombre ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill mostrar-informacion-icono" viewBox="0 0 16 16">
                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill guardar-informacion-icono" viewBox="0 0 16 16">
                <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
            </svg>
        </div>
    </div>
<?php
}

function horarios_duplicados($horas){

    // array_count_values() cuenta cuántas veces aparece cada valor en el array
    // Si $horarios = [1, 2, 1, 3], entonces $horarios_contados = [1 => 2, 2 => 1, 3 => 1]
    // Esto significa: el horario 1 aparece 2 veces, el 2 aparece 1 vez, el 3 aparece 1 vez
    $horarios_contados = array_count_values($horas);
    
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
    return $horarios_duplicados;
}

function verificar_inasistencia($con, $fecha, $horarios, $ci_profesor){
    $horarios_con_falta = array();

    //Se declaran los errores como false ya que al inicio, no hay errores
    $error = false;
    $mensaje_error = "";

    //Query que trae las inasistencias segun una fecha, un horario y un profesor
    $query_verificar_falta = "SELECT h.hora_inicio, h.hora_final FROM inasistencia i
                              INNER JOIN horarios h ON h.id_horario = i.id_horario
                              WHERE i.fecha_inasistencia = ? AND i.id_horario = ? AND i.ci_profesor = ?";

    $stmt_inasistencias = $con->prepare($query_verificar_falta);

    //Para cada horario ingresado, se realiza una query
    foreach ($horarios as $id_horario){
        $stmt_inasistencias->bind_param("sii", $fecha, $id_horario, $ci_profesor);
        $stmt_inasistencias->execute();
        $resultado_verificar_inasistencia = $stmt_inasistencias->get_result();

        //Si el numero de rows (filas) es distinto a 0 (quiere decir que hay inasistencia) se guarda en el array de horarios_con_falta
        if ($resultado_verificar_inasistencia->num_rows !== 0){
            $horario_info = $resultado_verificar_inasistencia->fetch_assoc();
            $horarios_con_falta[] = $horario_info['hora_inicio'] . '-' . $horario_info['hora_final'];
        }
    }

    $stmt_inasistencias->close();

    // Verificar DESPUES del foreach si hay mas de un valor en el array. En caso positivo se marca error
    if (count($horarios_con_falta) > 0) {
        $error = true;
        $mensaje_error = "El profesor ya tiene faltas registradas en los siguientes horarios: " . implode(", ", $horarios_con_falta);
    }

    return [$error, $mensaje_error];
}

function obtener_hora_calendario(){
    // SOLO PARA TESTING - Comentar para usar con la fecha actual
                  //yyyy-mm-dd
    // $fecha_test = '2025-12-08'; // Miércoles - Si quieren testear, cambien la fecha est
    // $base_time = strtotime($fecha_test);

    // Para uso actual usar esto (comentar las lineas de arriba):
    $base_time = time();

    return $base_time;
}

?>