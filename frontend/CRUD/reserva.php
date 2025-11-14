<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_once('../backend/queries.php');
include_once('../backend/helpers.php');

if (!isset($_SESSION['ci'])) {
    include_once('error.php');
    exit;
}

if (!isset($_SESSION['nivel_acceso'])) {
    $ci = $_SESSION['ci'];
    $result = reservaMostrar($ci);
} else {
    $result = reservaMostrar2();
}


?>

<div class="div-mostrar-datos">
    <h1><?= t("header_reservations") ?></h1>
</div>

<!-- Vista para PC -->
<div class="datos-grid reserva-grid">
    <div class="grid-header reserva-header">
        <div class="grid-cell id">ID</div>
        <div class="grid-cell nombre-titulo"><?= t("label_date") ?></div>
        <div class="grid-cell nombre-titulo"><?= t("label_ci_professor") ?></div>
        <div class="grid-cell nombre-titulo"><?= t("option_salon") ?></div>
        <div class="grid-cell nombre-titulo"><?= t("label_schedule") ?></div>
        <div class="grid-cell boton-titulo"><?= t("btn_delete") ?></div>
    </div>

    <?php while ($row = mysqli_fetch_array($result)): ?>
        <div class="grid-row reserva-row mostrar-datos">
            <div class="grid-cell"><?= htmlspecialchars($row['id_reserva']) ?></div>
            <div class="grid-cell"><?= htmlspecialchars($row['fecha_reserva']) ?></div>
            <div class="grid-cell"><?= htmlspecialchars($row['ci_profesor']) ?></div>
            <div class="grid-cell"><?= htmlspecialchars($row['nombre']) ?></div>
            <div class="grid-cell">
                <?= htmlspecialchars($row['hora_inicio'] . " - " . $row['hora_final']) ?>
            </div>
            <div class="grid-cell">
                <a href="#"
                    class="boton-datos-eliminar boton-eliminar-reserva botones-datos"
                    data-id="<?= htmlspecialchars($row['id_reserva']) ?>">
                    <?= t("btn_delete") ?>
                </a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Vista para celular -->
<?php mysqli_data_seek($result, 0); ?>
<div class="flex-mostrar-datos">
    <?php while ($row = mysqli_fetch_array($result)): ?>
        <?php $titulo = "Reserva #" . htmlspecialchars($row['id_reserva']); ?>
        <div class="datos-header-celu">
            <?= toggle_mostrar_info($titulo) ?>
            <div class="informacion-escondida">
                <div class="datos-tabla-flex">
                    <div class="grid-cell"><?= t("label_date") ?> <?= htmlspecialchars($row['fecha_reserva']) ?></div>
                    <div class="grid-cell"><?= t("label_ci_professor") ?> <?= htmlspecialchars($row['ci_profesor']) ?></div>
                    <div class="grid-cell"><?= htmlspecialchars($row['nombre']) ?></div>
                    <div class="grid-cell">
                        <?= t("label_schedule") ?><?= htmlspecialchars($row['hora_inicio'] . " - " . $row['hora_final']) ?>
                    </div>
                </div>

                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-reserva botones-datos"
                        data-id="<?= htmlspecialchars($row['id_reserva']) ?>">
                        <?= t("btn_delete") ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php echo boton_eliminar("overlay-reserva", "la reserva", "confirmar-reserva", "cancelar-reserva") ?>