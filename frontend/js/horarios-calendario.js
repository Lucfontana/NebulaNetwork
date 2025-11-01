const select = document.getElementById("select-horarios");
const selectSalones = document.getElementById("div-salones");
const selectCursos = document.getElementById("div-curso");

// Al cargar la pagina, restaurar el valor guardado
document.addEventListener('DOMContentLoaded', () => {
    const valorGuardado = localStorage.getItem('horarioSeleccionado');

    if (valorGuardado) {
        select.value = valorGuardado;
        //Ejecutar la logica con el valor guardado
        mostrarOpcion(valorGuardado);
    }

    // Escuchar cambios en el select
    if (select) {
        select.addEventListener("change", function() {
            const valorSeleccionado = this.value;
            //Guardar en localStorage
            localStorage.setItem('horarioSeleccionado', valorSeleccionado);
            //Mostrar la opcion correspondiente
            mostrarOpcion(valorSeleccionado);
        });
    }
});

//Funcion para mostrar/ocultar opciones segúun el valor
function mostrarOpcion(valor) {
    if (valor == 2) {
        selectSalones.style.display = "block";
        selectCursos.style.display = "none";
    } else if (valor == 1) {
        selectSalones.style.display = "none";
        selectCursos.style.display = "block";
    }
}

