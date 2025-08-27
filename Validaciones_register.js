const form = document.querySelectorAll("form-registro");
const RegistroBoton = document.querySelectorAll("#envRegistro");


RegistroBoton.forEach((registroboton, index) => {

    form.addEventListener("submit", function(event) {
        event.preventDefault();
        const CI = Number(document.getElementById("CI").value);
        const a = Number(document.getElementById("CI").value);
    })

})