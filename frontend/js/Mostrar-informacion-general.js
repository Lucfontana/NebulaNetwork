const titulo = document.getElementById("titulo-mostrar-informacion");

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
            seccionSeleccionada.style.display = 'block';
        }
    }
});

// Ocultar todo al cargar
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.seccion-oculta').forEach(seccion => {
        seccion.style.display = 'none';
    });
});
