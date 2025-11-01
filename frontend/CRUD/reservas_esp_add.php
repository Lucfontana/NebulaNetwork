<?php include_once("functions.php")?>

<div id="div-dialogs">
    <div class="overlay">
        <div class="dialogs" id="dialogs">
            <button class="btn-Cerrar" type="button"><img class="cruz-register" src="/frontend/img/cruz.png"
                    alt=""></button>
            <form class="registro-div reserva-form">
                <h1><?= t("Reservar espacio") ?></h1>
                <hr>
                <div class="div-labels"><i><b>Nota:</b> Solamente puedes reservar espacios en los momentos que tienes
                        clases.</i></div>
                <div class="div-labels">
                    <label for="dia" class="label">En el dia:</label>
                    <input type="date" name="dia_reserva" id="dia_reserva" class="input-register" required>
                </div>
                <div class="div-labels">
                    <label for="espacio" class="label">Espacio a reservar: </label>
                    <select name="espacio_reservar" id="espacio_reservar" type="text" class="input-register">
                        <option value=""></option>
                        <?php while ($row = mysqli_fetch_array($espacios_sin_general)): ?>
                            <option value="<?= $row['id_espacio'] ?>">
                                <?= $row['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="div-labels" id="horas_reserva">
                    <label for="nose" class="label">Cantidad de horas a reservar:</label>
                    <input type="number" name="cantidad_horas_reserva" id="cantidad_horas_reserva"
                        class="input-register" required>
                </div>
                <div id="campos-reservas"></div>
                <div class="div-botones-register">
                    <input class="btn-enviar-registro" type="submit" value="Registrar"
                        name="registrarReservaEspacio"></input>
                </div>
            </form>
        </div>
    </div>
</div>