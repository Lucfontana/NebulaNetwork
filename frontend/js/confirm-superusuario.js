import { verificarString, verificarEmail, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-super");
  const btnCancelar = document.getElementById("cancelar-super");
  const btnConfirmar = document.getElementById("confirmar-super");
  const overlayEdit = document.getElementById("overlay-edit-super");
  const btnCancelarEdit = document.getElementById("cancelarEdit-super");

  let editID = null;
  let nombre = null;
  let apellido = null;
  let nivel = null;

  let currentId = null;


  // Abrir modal y guardar id
  document.querySelectorAll(".boton-eliminar-super").forEach(boton => {
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
      window.location.href = `/backend/functions/SuperUsuarios/delete.php?id=${currentId}`;
    }
  });

  document.querySelectorAll(".boton-editar-super").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();

      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      nombre = botonEditar.dataset.nombre;
      apellido = botonEditar.dataset.apellido;
      nivel = botonEditar.dataset.nivel;
      

      document.getElementById("id_edit_super").value = editID;
      document.getElementById("name_edit_super").value = nombre;
      document.getElementById("apellido_edit_super").value = apellido;
      document.getElementById("nivel_edit_super").value = nivel;
      document.getElementById("email_edit_super").value = botonEditar.dataset.email;

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

  document.getElementById("form-update-super").addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita el submit normal
      
    let nombreInput = document.getElementById("name_edit_super").value;
    let apellidoInput =  document.getElementById("apellido_edit_super").value;
    let emailInput =  document.getElementById("email_edit_super").value;
    
    // Validaciones
    if (!verificarString(nombreInput, "nombre")) {
        return;
    }
    if (!verificarString(apellidoInput, "apellido")) {
        return;
    }
    if (!verificarEmail(emailInput)) {
        return;
    }

    // Si pasa las validaciones, envía el formulario
    const form = e.target;
    const fd = new FormData(form);

    try {
      const res = await fetch("/backend/functions/SuperUsuarios/edit.php", {
        method: "POST",
        body: fd
      });

      const data = await res.json();
      let mensaje = data.message;

      if (data.success) {
        alerta_success_update(mensaje, "/frontend/Mostrar_informacion.php");
      } else {
        alerta_fallo(mensaje);
      }
    } catch (err) {
      console.error(err);
      alerta_fallo("Error de conexión");
    }
  });
});


