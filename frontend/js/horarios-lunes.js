const select = document.getElementById("select-horarios");
const selectSalones = document.getElementById("div-salones");
const selectCursos = document.getElementById("div-curso");


select.addEventListener("change", function() {
    if (select.value = 2) {
        selectSalones.style.display = "block";
        selectCursos.style.display = "none";
    } else if (select.value = 1) {
        selectSalones.style.display = "none";
        selectCursos.style.display = "block";
    }
})
   
