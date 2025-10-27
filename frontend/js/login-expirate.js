const sesionIniciada = document.getElementById("session-status")?.textContent;
//guardamos el contenido del div session-status del nav.pgp en la constante

//si la sesión esta iniciada el contenido del div es 2, entonces verificamos que sea 2
if (sesionIniciada === "2") {
    let n = 900; // 15 minutos en segundos

    //Resetear temporizador con interacción del usuario
    ["mousemove", "keydown", "click", "scroll", "touchstart"].forEach(event => {
        document.addEventListener(event, () => {
            n = 900; // Reset a 15 minutos
        }, { passive: true }); //Mejora el performance
    });


    //Crea intervalos de tiempo en el que n va decreciendo 1 cada segundo
    const interval = setInterval(() => {
        n--;

        // Cuando llegue a 0, cerrar sesión
        if (n <= 0) {
            clearInterval(interval);
            window.location.href = "../../backend/login/logout.php";
        }

    }, 1000);
}