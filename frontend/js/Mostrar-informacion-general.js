const titulo = document.getElementById("titulo-mostrar-informacion");
const botonMostrar = document.querySelectorAll(".mostrar-informacion-oculta");
const informacionEscondida = document.querySelectorAll(".informacion-escondida");

botonMostrar.forEach((mostrar, index) => {
    mostrar.addEventListener("click", function() {
        if (informacionEscondida[index].style.display == "flex") {
            informacionEscondida[index].style.display = "none"
        } else {
            informacionEscondida[index].style.display = "flex"
        }
    })
});

document.getElementById('informacion-change').addEventListener('change', function() {
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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.seccion-oculta').forEach(seccion => {
        seccion.style.display = 'none';
    });
});
