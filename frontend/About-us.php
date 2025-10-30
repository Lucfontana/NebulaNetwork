<?php
include_once 'functions.php';
session_start();
?>
<style>
    .hdrBackground {
        padding-bottom: 4rem;
    }

    .container-aboutus {
        display: flex;
        flex-direction: column;
        width: 100%;
        align-items: center;
        justify-content: center;
        padding-bottom: 5rem;
        padding-top: 5rem;
    }

    .miembro {
        text-align: center;
        background: linear-gradient(135deg, rgba(207, 224, 255, 1), rgba(178, 197, 255, 1));
        border: 2px solid rgba(0, 26, 255, 0.4);
        border-radius: 30px;
        backdrop-filter: blur(10px);
        padding: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        width: 280px;
        position: relative;
        overflow: hidden;
    }

    .miembro>img {
        z-index: 1000;
    }

    /* Efecto de partículas en el fondo - Crea un contenido antes del section */
    .miembro::before {
        content: '';
        /* Crea un objeto vacio */
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        /* Para que al rotarlo siga abarcando todo el contenedor */
        height: 200%;
        background: linear-gradient(45deg,
                transparent,
                rgba(78, 81, 243, 0.45),
                transparent);
        /* background con transperencia :v pacman*/
        transform: rotate(45deg);
        /* hacia donde se va mover, en este caso de esquina inferior izquierda a esquina superior derecha */
        transition: all 1s ease;
    }

    .miembro:hover::before {
        left: 100%;
        /* Mueve el degradado fuera del contenedor */
    }

    .miembro:hover {
        border-color: rgba(74, 77, 255, 0.6);
        box-shadow: 0 20px 60px rgba(83, 80, 252, 0.3);
        /* Iluminación */
    }

    .img-logo-nosotros {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: aliceblue;
        border-radius: 50%;
        width: 250px;
        height: 250px;
        margin: 30px;
        cursor: pointer;
        transition: 1s;
    }

    .img-logo-nosotros:hover {
        scale: 1.05;
        transition: 1s;
    }

    .container-miembros {
        display: flex;
        justify-content: space-around;
        gap: 10px;
        margin-top: 20px;
        color: black;
        cursor: pointer;
    }

    .github-vinculo {
        color: black;
    }
</style>

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