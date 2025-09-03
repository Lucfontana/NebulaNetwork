const form = document.querySelectorAll("#form-registro");
const RegistroBoton = document.querySelectorAll("#envRegistro");
const botonCerrar = document.querySelectorAll(".btn-Cerrar");

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


botonCerrar.forEach((cerrar, index) => {
    cerrar.addEventListener("click", function () {
        event.preventDefault();

        const CI = Number(document.querySelectorAll("#CI"));
        const passwd = document.querySelectorAll("#ontrasena");
        const name = document.querySelectorAll("#name");
        const apellido = document.querySelectorAll("#apellido");
        const email = document.querySelectorAll("#email");
        const nacimiento = Date(document.gquerySelectorAll("#nac"));
        const direccion = document.querySelectorAll("#direc");
        const acceso = document.querySelectorAll("#acceso");
        const description = document.querySelectorAll("#description");
        const tipo = document.querySelectorAll("#tipo");
        const capacidad = Number(document.querySelectorAll("#capacity"));
        const equipamiento = document.querySelectorAll("#equip");
        const prerequisitos = document.querySelectorAll("#requisitos");
        const cupos = Number(document.querySelectorAll("#cupos"));
        const hora_inicio = document.querySelectorAll("#hora_inicio");
        const hora_final = document.querySelectorAll("#hora_final");

        CI.textContent = null;
        name.textContent = null;
        apellido.textContent = null;
        email.textContent = null;
        nacimiento.textContent = null;
        direccion.textContent = null;
        acceso.textContent = null;
        description.textContent = null;
        tipo.textContent = null;
        capacidad.textContent = null;
        equipamiento.textContent = null;
        prerequisitos.textContent = null;
        cupos.textContent = null;
        hora_inicio.textContent = null;
        hora_final.textContent = null;
    })
})


function validacion() {
    
}