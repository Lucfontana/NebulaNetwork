import { verificarNombreEspecial, verificarCapacidad, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-curso");
  const btnCancelar = document.getElementById("cancelar-curso");
  const btnConfirmar = document.getElementById("confirmar-curso");
  const overlayEdit = document.getElementById("overlay-edit-curso");
  const btnCancelarEdit = document.getElementById("cancelarEdit-curso");

  let currentId = null;

  // Abrir modal eliminar - SOLO para cursos
  document.querySelectorAll(".boton-eliminar-curso").forEach(boton => {
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

  btnCancelar.addEventListener("click", () => {
    overlay.style.opacity = "0";
    overlay.style.transition = "0.5s";
    currentId = null;
    setTimeout(() => {
      overlay.style.display = "none"; 
    }, 500);
  });

  btnConfirmar.addEventListener("click", () => {
    if (currentId) {
      window.location.href = `/backend/functions/Cursos/delete.php?id=${currentId}`;
    }
  });

  // Abrir modal editar - SOLO para cursos
  document.querySelectorAll(".boton-editar-curso").forEach(botonEditar => {
    botonEditar.addEventListener("click", (e) => {
      e.preventDefault();
      overlayEdit.style.display = "flex";

      const editID = botonEditar.dataset.id;
      const nombre = botonEditar.dataset.nombre;
      const capacidad = botonEditar.dataset.capacidad;

      document.getElementById("id_edit_curso").value = editID;
      document.getElementById("name_edit_curso").value = nombre;
      document.getElementById("capacidad_edit_curso").value = capacidad;

      setTimeout(() => {
        overlayEdit.style.opacity = "1";
        overlayEdit.style.transition = "0.5s";
      }, 1);
    });
  });

  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.opacity = "0";
    overlayEdit.style.transition = "0.5s";
    setTimeout(() => {
      overlayEdit.style.display = "none";
    }, 500);
  });

  // Submit del formulario
  const formUpdate = document.getElementById("form-update-curso");
  if (formUpdate) {
    formUpdate.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      let nombreInput = document.getElementById("name_edit_curso").value;
      let capacidadInput = document.getElementById("capacidad_edit_curso").value;

      if (!verificarNombreEspecial(nombreInput)) {
        return;
      }
      if (!verificarCapacidad(capacidadInput)) {
        return;
      }

      const fd = new FormData(e.target);

      try {
        const res = await fetch("/backend/functions/Cursos/edit.php", {
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