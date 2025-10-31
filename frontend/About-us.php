<?php
include_once 'functions.php';
session_start();
?>

<title>About Us</title>
<?php include './Complementos/nav.php'; ?>

<body>
    <div class="hdrBackground" id="hdrBackground">
        <div class="container-aboutus">
            <a target="_blank" href="https://github.com/Lucfontana/NebulaNetwork">
                <div class="img-logo-nosotros">
                    <img src="./img/logologo.png">
                </div>
            </a>
            <h1>¡Somos Nebula Network!</h1>
            <h5>Un equipo de desarrollo de software dedicado y pasional hacia el desarrollo de aplicaciones web.</h5>
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
</body>
<?php include './Complementos/footer.php'; ?>