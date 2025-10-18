const titulo = document.getElementById("titulo-mostrar-informacion");
const botonMostrar = document.querySelectorAll(".mostrar-informacion-oculta");
const informacionEscondida = document.querySelectorAll(".informacion-escondida");
const botonGuardar = document.querySelectorAll(".icono-guardar-informacion");

botonMostrar.forEach((mostrar, index) => {
    mostrar.addEventListener("click", function () {
        informacionEscondida[index].style.display = "flex";
        informacionEscondida[index].style.transition = "1s";
        mostrar.style.display = "none";
        botonGuardar[index].style.display = "flex";

        setTimeout(() => {
            informacionEscondida[index].style.transform = "translateY(0%)";
            informacionEscondida[index].style.opacity = "1";
            informacionEscondida[index].style.transition = "0.5s";
        }, 0.1)
    });
});

botonGuardar.forEach((guardar, index) => {
    guardar.addEventListener("click", function () {
        informacionEscondida[index].style.transform = "translateY(-100%)";
        informacionEscondida[index].style.opacity = "0";
        informacionEscondida[index].style.transition = "0.5s";
     guardar.style.display = "none";
        botonMostrar[index].style.display = "flex";

        setTimeout(() => {
            informacionEscondida[index].style.display = "none";
        }, 500)
    });
});

document.getElementById('informacion-change').addEventListener('change', function () {
    titulo.style.display = "none";
    document.querySelectorAll('.seccion-oculta').forEach(seccion => {
        seccion.style.display = 'none';
    })
    // Mostrar la sección seleccionada
    const valor = this.value;
    if (valor == "0") {
        titulo.style.display = "flex";
    } else if (valor) {
        const seccionSeleccionada = document.querySelector(`[data-seccion="${valor}"]`);
        if (seccionSeleccionada) {
            seccionSeleccionada.style.display = 'flex';
        }
    }
});

// Ocultar todo al cargar
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.seccion-oculta').forEach(seccion => {
        seccion.style.display = 'none';
    });
});
