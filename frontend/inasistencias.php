<?php
include_once('../backend/db/conexion.php');
include_once 'functions.php';
include_once('../backend/helpers.php');

$connect = conectar_a_bd();

if (!isset($_SESSION['ci'])) {
    include_once('error.php');
    exit;
}

$ci = $_SESSION['ci'];

// Consulta preparada
$sql = "SELECT i.*, h.hora_inicio, h.hora_final, h.tipo
        FROM inasistencia i
        INNER JOIN horarios h ON i.id_horario = h.id_horario
        WHERE i.ci_profesor = ?";

$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $ci);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="div-mostrar-datos">
    <h1>Inasistencias</h1>
</div>

<!-- Vista para PC -->
<div class="datos-grid inasistencias-grid">
    <div class="grid-header inasistencias-header">
        <div class="grid-cell id">ID</div>
        <div class="grid-cell nombre-titulo">Fecha</div>
        <div class="grid-cell nombre-titulo">CI Profesor</div>
        <div class="grid-cell nombre-titulo">Horario</div>
        <div class="grid-cell boton-titulo">Eliminar</div>
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
                    Eliminar
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
                    <div class="grid-cell">Fecha: <?= htmlspecialchars($row['fecha_inasistencia']) ?></div>
                    <div class="grid-cell">CI Profesor: <?= htmlspecialchars($row['ci_profesor']) ?></div>
                    <div class="grid-cell">
                        Horario: <?= htmlspecialchars($row['hora_inicio'] . " - " . $row['hora_final']) ?>
                    </div>
                </div>

                <div class="grid-cell">
                    <a href="#"
                        class="boton-datos-eliminar boton-eliminar-inasistencia botones-datos"
                        data-id="<?= htmlspecialchars($row['id_inasistencia']) ?>">
                        Eliminar
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php echo boton_eliminar("overlay-inasistencia", "la inasistencia", "confirmar-inasistencia", "cancelar-inasistencia") ?>