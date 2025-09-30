const changeName = document.getElementById('editar-nombre');
const dialogChangeName = document.getElementById('dialog-change-name');
const closeChangeName = document.getElementById('close-change-name');
const btnCancelarEdit = document.getElementById("cancelarEdit");

changeName.addEventListener("click", function (a) {
    a.preventDefault();

    dialogChangeName.style.display = "flex";
    dialogChangeName.style.transform = "translateY(5%)";
    dialogChangeName.style.opacity = "1";
    dialogChangeName.style.transition = "0.5s";

    nombre = changeName.dataset.name;
    document.getElementById("name_edit").value = nombre;


})

 btnCancelarEdit.addEventListener("click", () => {
    dialogChangeName.style.display = "none";
});

const btnActualizarProfesor = document.getElementById("actualizar_profesor");
const btnActualizarAdscripta = document.getElementById("actualizar_adscripta");
const btnActualizarSecretaria = document.getElementById("actualizar_secretaria");
const btnActualizarAdministrador = document.getElementById("actualizar_administrador");



