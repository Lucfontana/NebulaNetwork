<?php

function cargar_horarios($query, $dias, $materias_por_dia, $dato_mostrar){
    ?>
        <?php mysqli_data_seek($query, 0); // Reset del puntero 
    ?>
    <div class="datos-body"> 
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <div class="datos-row mostrar-datos">
                <div class="horas-dato"><?= $row['hora_inicio'] ?> - <?= $row['hora_final'] ?></div>
                <?php foreach ($dias as $dia): ?>
                    <?php
                        $mostro = false;
                        foreach ($materias_por_dia[$dia] as $m) {
                            if ($m['hora_inicio'] == $row['hora_inicio']) {
                                $clase_inasistencia = isset($m['tiene_inasistencia']) && $m['tiene_inasistencia'] ? 'inasistencia-marcada' : '';
                                echo "<div class='dia-dato {$clase_inasistencia}'>
                                <strong>{$m[$dato_mostrar]}</strong>
                                </div>";
                                $mostro = true;
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

function cabecera_horarios(){
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



?>