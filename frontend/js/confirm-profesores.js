import { verificarString, verificarEmail,verificarFechanacimiento, verificarDireccion, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay");
  const btnCancelar = document.getElementById("cancelar");
  const btnConfirmar = document.getElementById("confirmar");
  const overlayEdit = document.getElementById("overlay-edit");
  const btnCancelarEdit = document.getElementById("cancelarEdit");

  let editID = null;
  let nombre = null;
  let apellido = null;
  let email = null;
  let fnac = null;
  let direccion = null;

  let currentId = null;


  // Abrir modal y guardar id
  document.querySelectorAll(".boton-datos-eliminar").forEach(boton => {
    boton.addEventListener("click", (e) => {
      e.preventDefault();
      currentId = boton.dataset.id;
      overlay.style.display = "flex";

      setTimeout(() => {
       overlay.style.opacity = "1";
       overlay.style.transition = "0.5s";
    }, 1)
    });
  });

  // Cancelar
  btnCancelar.addEventListener("click", () => {
    overlay.style.opacity = "0";
    overlay.style.transition = "0.5s";
    currentId = null;
    setTimeout(() => {
       overlay.style.display = "none"; 
    }, 500)
  });

  // Confirmar: redirigir a tu PHP de borrado
  btnConfirmar.addEventListener("click", () => {
    if (currentId) {
      window.location.href = `/backend/functions/Profesores/delete.php?id=${currentId}`;
    }
  });

  document.querySelectorAll(".boton-datos-editar").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();

      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      nombre = botonEditar.dataset.nombre;
      apellido = botonEditar.dataset.apellido;
      email = botonEditar.dataset.email;
      fnac = botonEditar.dataset.fnac;
      direccion = botonEditar.dataset.direccion;

      document.getElementById("id_edit").value = editID;
      document.getElementById("name_edit").value = nombre;
      document.getElementById("apellido_edit").value = apellido;
      document.getElementById("email_edit").value = email;
      document.getElementById("fnac_edit").value = fnac;
      document.getElementById("direccion_edit").value = direccion;
      
      setTimeout(() => {
       overlayEdit.style.opacity = "1";
       overlayEdit.style.transition = "0.5s";
    }, 1)
    })
  })

  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.opacity = "0";
    overlayEdit.style.transition = "0.5s";
    editID = null;
    setTimeout(() => {
       overlayEdit.style.display = "none";
    }, 500)
  });

  document.getElementById("form-update").addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita el submit normal
      
    let nombreInput = document.getElementById("name_edit").value;
    let apellidoInput = document.getElementById("apellido_edit").value;
    let emailInput = document.getElementById("email_edit").value;
    let fnacInput = document.getElementById("fnac_edit").value;
    let direccionInput = document.getElementById("direccion_edit").value;
   
    

    // Validaciones
        if (!verificarString(nombreInput, "nombre")) {
            e.preventDefault();
            return;
        }
        if (!verificarString(apellidoInput, "apellido")) {
            e.preventDefault();
            return;
        }
        if (!verificarEmail(emailInput)) {
            e.preventDefault();
            return;
        }
        if (!verificarFechanacimiento(fnacInput)) {
            e.preventDefault();
            return;
        }
        if (!verificarDireccion(direccionInput)) {
            e.preventDefault();
            return;
        }

    // Si pasa las validaciones, envía el formulario
    const form = e.target;
    const fd = new FormData(form);

    try {
      const res = await fetch("/backend/functions/Profesores/edit.php", {
        method: "POST",
        body: fd,
        credentials: "same-origin"
      });

      const data = await res.json();
      let mensaje = data.message;

      if (data.success) {
        alerta_success_update(mensaje, "/frontend/Profesores.php");
      } else {
        alerta_fallo(mensaje);
      }
    } catch (err) {
      console.error(err);
      alerta_fallo("Error de conexión");
    }
  });
});


