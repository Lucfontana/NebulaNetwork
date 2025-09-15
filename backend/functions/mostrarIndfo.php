<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
            <?php
            include '../../frontend/nav.php';
            ?>

            <?php if (isset($insercion_dateishons)): ?>
                <main class="contenido" id="contenido"><?= $insercion_dateishons ?></main>
                <script src="../sideMenu.js"></script>
            <?php endif; ?>
</body>
</html>