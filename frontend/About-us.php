<?php
include_once 'functions.php';
session_start();
?>

<title><?= t("aboutus_title") ?></title>
<?php include './Complementos/nav.php'; ?>

<body>
    <div class="body-aboutus">
        <div class="container-aboutus">
            <a class="img-logo-nosotros" target="_blank" href="https://github.com/Lucfontana/NebulaNetwork">
                <div>
                    <img src="./img/logologo.png">
                </div>
            </a>
            <h1><?= t("aboutus_heading") ?></h1>
            <h5><?= t("aboutus_description") ?></h5>
            <div class="container-miembros">
                <a class="github-vinculo" target="_blank" href="https://github.com/MineAbel">
                    <section class="miembro">
                        <h2 style="font-size:28px;">Lautaro Cardozo</h2>
                    </section>
                </a>
                <a class="github-vinculo" target="_blank" href="https://github.com/isisgz">
                    <section class="miembro">
                        <h2 style="font-size:28px;">Isis Gonzalez</h2>
                    </section>
                </a>
                <a class="github-vinculo" target="_blank" href="https://github.com/Abril-fujo">
                    <section class="miembro">
                        <h2 style="font-size:28px;">Abril Manek</h2>
                    </section>
                </a>
                <a class="github-vinculo" target="_blank" href="https://github.com/Lucfontana">
                    <section class="miembro">
                        <h2 style="font-size:28px;">Luca Fontana</h2>
                    </section>
                </a>
            </div>
        </div>
    </div>
    <?php include './Complementos/footer.php'; ?>
</body>
