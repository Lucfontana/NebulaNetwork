<?php

include_once("functions.php");

?>

<div id="div-dialogs">
    <div class="overlay">
        <div class="dialogs" id="dialogs">
            <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                    alt=""></button>
            <form class="registro-div inasistencia-form">
                <h1><?= t("btn_register_absence") ?></h1>
                <hr>

                <div class="div-labels">
                    <label for="dia" class="label"><?= t("label_day_absence") ?></label>
                    <input type="date" name="dia_falta" id="dia_falta" class="input-register" required>
                </div>

                <div class="div-labels" id="horas_falta">
                    <label for="nose" class="label"><?= t("label_hours_to_absent") ?></label>
                    <input type="number" name="cantidad_horas_falta" id="cantidad_horas_falta" class="input-register"
                        required>
                </div>

                <div class="div-labels" id="horas_clase_profe"></div>

                <div id="campos-dinamicos"></div>

                <div class="div-botones-register">
                    <input class="btn-enviar-registro" type="submit" value="Registrar" name="registrarFalta"></input>
                </div>

            </form>
        </div>
    </div>
</div>