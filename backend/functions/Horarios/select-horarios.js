document.getElementById("select-horarios").addEventListener("change", function () {
    const valor = this.value;
    const divSalones = document.getElementById("div-salones");
    const divCurso = document.getElementById("div-curso");

    if (valor === "2") {
        divSalones.style.display = "block"; // Muestra el select de salones
        divCurso.style.display = "none"; // Oculta el select de cursos
    } else {
        divSalones.style.display = "none"; // Lo oculta
        divCurso.style.display = "block"; // Lo oculta
    }
});