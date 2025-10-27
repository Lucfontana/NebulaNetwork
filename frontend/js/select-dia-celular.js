document.addEventListener("DOMContentLoaded", function() {
    const selectDias = document.getElementById("select-dias");
    const contenedor = document.getElementById("contenedor-horarios-celular");
    

    //Muestra el dia seleccionado segun
    selectDias.addEventListener("change", function() {
        const diaSeleccionado = this.value;

        // Ocultar todos los horarios
        contenedor.querySelectorAll(".horario-dia").forEach(div => {
            div.style.display = "none";
        });

        // Mostrar solo el seleccionado
        const diaMostrar = document.getElementById("horario-" + diaSeleccionado);
        if (diaMostrar) {
            diaMostrar.style.display = "block";
        }
    });
});