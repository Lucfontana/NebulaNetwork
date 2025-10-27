const titulo = document.getElementById("titulo-mostrar-informacion");
const botonMostrar = document.querySelectorAll(".mostrar-informacion-oculta");
const iconoMostrar = document.querySelectorAll(".mostrar-informacion-icono");
const iconoGuardar = document.querySelectorAll(".guardar-informacion-icono")
const informacionEscondida = document.querySelectorAll(".informacion-escondida");
const selectInformacion = document.getElementById('informacion-change');

//creamos una variable para establecer si hay que abrirlo o cerrarlo
let variable = 0;
botonMostrar.forEach((mostrar, index) => {
    mostrar.addEventListener("click", function () {
        if (variable === 0) {
            informacionEscondida[index].style.display = "flex";
            informacionEscondida[index].style.transition = "1s";
            iconoMostrar[index].style.display = "none";
            iconoGuardar[index].style.display = "flex";

            setTimeout(() => {
                informacionEscondida[index].style.transform = "translateY(0%)";
                informacionEscondida[index].style.opacity = "1";
                informacionEscondida[index].style.transition = "0.5s";
            }, 0.1)

            variable = 1;

        } else {
            informacionEscondida[index].style.transform = "translateY(-100%)";
            informacionEscondida[index].style.opacity = "0";
            informacionEscondida[index].style.transition = "0.5s";
            iconoGuardar[index].style.display = "none";
            iconoMostrar[index].style.display = "flex";

            setTimeout(() => {
                informacionEscondida[index].style.display = "none";
            }, 500)

            variable = 0;
        }
    });
});

// Función para mostrar la sección correspondiente
function mostrarSeccion(valor) {
    titulo.style.display = "none";
    document.querySelectorAll('.seccion-oculta').forEach(seccion => {
        seccion.style.display = 'none';
    });

    if (valor == "0") {
        titulo.style.display = "flex";
    } else if (valor) {
        const seccionSeleccionada = document.querySelector(`[data-seccion="${valor}"]`);
        if (seccionSeleccionada) {
            seccionSeleccionada.style.display = 'flex';
        }
    }
}

// Evento change del select - Guardar en localStorage
selectInformacion.addEventListener('change', function () {
    const valor = this.value;

    // Guardar en localStorage
    localStorage.setItem('seccionSeleccionada', valor);

    // Mostrar la sección
    mostrarSeccion(valor);
});

// Al cargar la página, restaurar el valor guardado
document.addEventListener('DOMContentLoaded', function () {
    // Ocultar todas las secciones inicialmente
    document.querySelectorAll('.seccion-oculta').forEach(seccion => {
        seccion.style.display = 'none';
    });

    // Recuperar el valor guardado en localStorage
    const valorGuardado = localStorage.getItem('seccionSeleccionada');

    if (valorGuardado) {
        // Establecer el valor del select
        selectInformacion.value = valorGuardado;

        // Mostrar la sección correspondiente
        mostrarSeccion(valorGuardado);
    }
});