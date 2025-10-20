const sesionIniciada = document.getElementById("session-status")?.textContent;

if (sesionIniciada === "2") {
    let n = 900; // 15 minutos en segundos (cambié de 10 a 900)

    //Resetear temporizador con interacción del usuario
    ["mousemove", "keydown", "click", "scroll", "touchstart"].forEach(event => {
        document.addEventListener(event, () => {
            n = 900; // Reset a 15 minutos
        }, { passive: true }); //Mejora el performance
    });

    const interval = setInterval(() => {
        n--;

        // Cuando llegue a 0, cerrar sesión
        if (n <= 0) {
            clearInterval(interval);
            window.location.href = "../../backend/login/logout.php";
        }

    }, 1000);
}