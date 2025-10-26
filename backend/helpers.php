<?php

function cargar_horarios($query, $dias, $materias_por_dia, $dato_mostrar, $dato_adicional)
{
?>
    <?php mysqli_data_seek($query, 0); ?>
    <div class="datos-body">
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="datos-row mostrar-datos">
                <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                <?php foreach ($dias as $dia): ?>
                    <?php
                    $mostro = false;
                    
                    // CAMBIO CRÍTICO: Filtrar por hora_inicio, hora_final E id_horario
                    foreach ($materias_por_dia[$dia] as $m) {
                        // Comparar también el id_horario para evitar duplicados
                        if ($m['hora_inicio'] == $row['hora_inicio'] && 
                            $m['hora_final'] == $row['hora_final'] &&
                            $m['id_horario'] == $row['id_horario']) {
                            
                            $clase_inasistencia = isset($m['tiene_inasistencia']) && $m['tiene_inasistencia'] ? 'inasistencia-marcada' : '';
                            echo "<div class='dia-dato {$clase_inasistencia}'>
                                <strong>{$m[$dato_mostrar]}</strong>
                                <small>{$m[$dato_adicional]}</small>
                                </div>";
                            $mostro = true;
                            break; // IMPORTANTE: Salir del loop después de encontrar la primera coincidencia
                        }
                    }
                    
                    if (!$mostro) {
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



?>