<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_Once('../backend/queries.php');
include_once('../backend/helpers.php');

if (!isset($_SESSION['ci'])) {
    include_once('error.php');
    exit;
}

if (!isset($_SESSION['nivel_acceso'])) {
    $ci = $_SESSION['ci'];
    $result = inasistenciasMostrar($ci);
} else {
    $result = inasistenciasMostrar2();
}

?>

<div class="div-mostrar-datos">
    <h1><?= t("header_absences") ?></h1>
</div>

<!-- Vista para PC -->
<div class="datos-grid inasistencias-grid">
    <div class="grid-header inasistencias-header">
        <div class="grid-cell id">ID</div>
        <div class="grid-cell nombre-titulo"><?= t("table_date") ?></div>
        <div class="grid-cell nombre-titulo"><?= t("table_teacher_id") ?></div>
        <div class="grid-cell nombre-titulo"><?= t("aside_schedule") ?></div>
        <div class="grid-cell boton-titulo"><?= t("btn_delete") ?></div>
    </div>

    <?php while ($row = mysqli_fetch_array($result)): ?>
        <div class="grid-row inasistencias-row mostrar-datos">
            <div class="grid-cell"><?= htmlspecialchars($row['id_inasistencia']) ?></div>
            <div class="grid-cell"><?= htmlspecialchars($row['fecha_inasistencia']) ?></div>
            <div class="grid-cell"><?= htmlspecialchars($row['ci_profesor']) ?></div>
            <div class="grid-cell">
                <?= htmlspecialchars($row['hora_inicio'] . " - " . $row['hora_final']) ?>
            </div>
            <div class="grid-cell">
                <a href="#"
                    class="boton-datos-eliminar boton-eliminar-inasistencia botones-datos"
                    data-id="<?= htmlspecialchars($row['id_inasistencia']) ?>">
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
        <?php $titulo = "Inasistencia #" . htmlspecialchars($row['id_inasistencia']); ?>
        <div class="datos-header-celu">
            <?= toggle_mostrar_info($titulo) ?>
            <div class="informacion-escondida">
                <div class="datos-tabla-flex">
                    <div class="grid-cell"><?= t("label_date") ?>:  <?= htmlspecialchars($row['fecha_inasistencia']) ?></div>
                    <div class="grid-cell"><?= t("label_teacher_id") ?>:<?= htmlspecialchars($row['ci_profesor']) ?></div>
                    <div class="grid-cell">
                        <?= t("label_schedule") ?>:<?= htmlspecialchars($row['hora_inicio'] . " - " . $row['hora_final']) ?>
                    </div>
                </div>

                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-inasistencia botones-datos"
                        data-id="<?= htmlspecialchars($row['id_inasistencia']) ?>">
                          <?= t("btn_delete") ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php echo boton_eliminar("overlay-inasistencia", "la inasistencia", "confirmar-inasistencia", "cancelar-inasistencia") ?>