import { verificarString, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-asignatura");
  const btnCancelar = document.getElementById("cancelar-asignatura");
  const btnConfirmar = document.getElementById("confirmar-asignatura");
  const overlayEdit = document.getElementById("overlay-edit-asignatura");
  const btnCancelarEdit = document.getElementById("cancelarEdit-asignatura");
  
  let currentId = null;

  // Abrir modal eliminar - SOLO para asignaturas
  document.querySelectorAll(".boton-eliminar-asignatura").forEach(boton => {
    boton.addEventListener("click", (e) => {
      e.preventDefault();
      currentId = boton.dataset.id;
      overlay.style.display = "flex";
      setTimeout(() => {
        overlay.style.opacity = "1";
        overlay.style.transition = "0.5s";
      }, 1);
    });
  });

  // Cancelar eliminar
  btnCancelar.addEventListener("click", () => {
    overlay.style.opacity = "0";
    overlay.style.transition = "0.5s";
    currentId = null;
    setTimeout(() => {
      overlay.style.display = "none"; 
    }, 500);
  });

  // Confirmar eliminar
  btnConfirmar.addEventListener("click", () => {
    if (currentId) {
      window.location.href = `/backend/functions/asignaturas/delete.php?id=${currentId}`;
    }
  });

  // Abrir modal editar - SOLO para asignaturas
  document.querySelectorAll(".boton-editar-asignatura").forEach(botonEditar => {
    botonEditar.addEventListener("click", (e) => {
      e.preventDefault();
      overlayEdit.style.display = "flex";

      const editID = botonEditar.dataset.id;
      const nombre = botonEditar.dataset.nombre;

      document.getElementById("id_edit_asignatura").value = editID;
      document.getElementById("name_edit_asignatura").value = nombre;

      setTimeout(() => {
        overlayEdit.style.opacity = "1";
        overlayEdit.style.transition = "0.5s";
      }, 1);
    });
  });

  // Cancelar editar
  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.opacity = "0";
    overlayEdit.style.transition = "0.5s";
    setTimeout(() => {
      overlayEdit.style.display = "none";
    }, 500);
  });

  // Submit del formulario
  const formUpdate = document.getElementById("form-update-asignatura");
  if (formUpdate) {
    formUpdate.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      let nombreInput = document.getElementById("name_edit_asignatura").value;

      if (!verificarString(nombreInput, "Nombre")) {
        return;
      }

      const fd = new FormData(e.target);

      try {
        const res = await fetch("/backend/functions/asignaturas/edit.php", {
          method: "POST",
          body: fd,
          credentials: "same-origin"
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
  }
});