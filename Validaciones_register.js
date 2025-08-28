const form = document.querySelectorAll("form-registro");
const RegistroBoton = document.querySelectorAll("#envRegistro");

//Atributos



RegistroBoton.forEach((registroboton, index) => {

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const CI = Number(document.querySelectorAll("#CI").value);
        const passwd = document.querySelectorAll("#ontrasena").value;
        const name = document.querySelectorAll("#name").value;
        const apellido = document.querySelectorAll("#apellido").value;
        const email = document.querySelectorAll("#email").value;
        const nacimiento = Date(document.gquerySelectorAll("#nac").value);
        const direccion = document.querySelectorAll("#direc").value;
        const acceso = document.querySelectorAll("#acceso").value;
        const description = document.querySelectorAll("#description").value;
        const tipo = document.querySelectorAll("#tipo").value;
        const capacidad = Number(document.querySelectorAll("#capacity").value);
        const equipamiento = document.querySelectorAll("#equip").value;
        const prerequisitos = document.querySelectorAll("#requisitos").value;
        const cupos = Number(document.querySelectorAll("#cupos").value);
        const hora_inicio = document.querySelectorAll("#hora_inicio").value;
        const hora_final = document.querySelectorAll("#hora_final").value;

    })
})

function validacion() {
    
}