import { verificarNombreEspecial, verificarCapacidad, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-espacio");
  const btnCancelar = document.getElementById("cancelar-espacio");
  const btnConfirmar = document.getElementById("confirmar-espacio");
  const overlayEdit = document.getElementById("overlay-edit-espacio");
  const btnCancelarEdit = document.getElementById("cancelarEdit-espacio");

  let currentId = null;

  // Abrir modal eliminar - SOLO para espacios
  document.querySelectorAll(".boton-eliminar-espacio").forEach(boton => {
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
      window.location.href = `/backend/functions/Espacios/delete.php?id=${currentId}`;
    }
  });

  // Abrir modal editar - SOLO para espacios
  document.querySelectorAll(".boton-editar-espacio").forEach(botonEditar => {
    botonEditar.addEventListener("click", (e) => {
      e.preventDefault();
      overlayEdit.style.display = "flex";

      const editID = botonEditar.dataset.id;
      const nombre = botonEditar.dataset.nombre;
      const tipo = botonEditar.dataset.tipo;
      const capacidad = botonEditar.dataset.capacidad;

      document.getElementById("id_edit_espacio").value = editID;
      document.getElementById("name_edit_espacio").value = nombre;
      document.getElementById("capacidad_edit_espacio").value = capacidad;
      document.getElementById("tipo_edit_espacio").value = tipo;

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
  const formUpdate = document.getElementById("form-update-espacio");
  if (formUpdate) {
    formUpdate.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      let nombreInput = document.getElementById("name_edit_espacio").value;
      let capacidadInput = document.getElementById("capacidad_edit_espacio").value;

      if (!verificarNombreEspecial(nombreInput)) {
        return;
      }
      if (!verificarCapacidad(capacidadInput)) {
        return;
      }

      const fd = new FormData(e.target);

      try {
        const res = await fetch("/backend/functions/Espacios/edit.php", {
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