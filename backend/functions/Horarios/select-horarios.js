//Busca en el documento HTML un elemento que tenga el id="select-horarios" (lista desplegable)
document.getElementById("select-horarios").addEventListener("change", function () {
//El evento "change" se activa cuando el usuario selecciona una opción diferente.

    const valor = this.value; //Dentro del evento, this se refiere al elemento <select>. 
                             // this.value obtiene el valor de la opción seleccionada.

    // se crean variables
    const divSalones = document.getElementById("div-salones");
    const divCurso = document.getElementById("div-curso");

    if (valor === "2") { //Si el valor del select es "2", entonces:
        divSalones.style.display = "block"; // Muestra el select de salones
        divCurso.style.display = "none"; // Oculta el select de cursos
    } else {
        divSalones.style.display = "none"; // Lo oculta
        divCurso.style.display = "block"; // Lo oculta
    }
});