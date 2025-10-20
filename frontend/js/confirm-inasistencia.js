import { verificarString, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-inasistencia");
  const btnCancelar = document.getElementById("cancelar-inasistencia");
  const btnConfirmar = document.getElementById("confirmar-inasistencia");
  const overlayEdit = document.getElementById("overlay-edit-inasistencia");
  const btnCancelarEdit = document.getElementById("cancelarEdit-inasistencia");

  let currentId = null;

  // --- ABRIR MODAL ELIMINAR (solo inasistencias) ---
  document.querySelectorAll(".boton-eliminar-inasistencia").forEach(boton => {
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

  // --- CANCELAR ELIMINAR ---
  btnCancelar.addEventListener("click", () => {
    overlay.style.opacity = "0";
    overlay.style.transition = "0.5s";
    currentId = null;
    setTimeout(() => {
      overlay.style.display = "none";
    }, 500);
  });

  // --- CONFIRMAR ELIMINAR ---
  btnConfirmar.addEventListener("click", () => {
    if (currentId) {
      window.location.href = `/backend/functions/inasistencias/delete.php?id=${currentId}`;
    }
  });

  // --- ABRIR MODAL EDITAR (solo inasistencias) ---
  document.querySelectorAll(".boton-editar-inasistencia").forEach(botonEditar => {
    botonEditar.addEventListener("click", (e) => {
      e.preventDefault();
      overlayEdit.style.display = "flex";

      const editID = botonEditar.dataset.id;
      const fecha = botonEditar.dataset.fecha;
      const horario = botonEditar.dataset.horario;

      document.getElementById("id_edit_inasistencia").value = editID;
      document.getElementById("fecha_edit_inasistencia").value = fecha;
      document.getElementById("horario_edit_inasistencia").value = horario;

      setTimeout(() => {
        overlayEdit.style.opacity = "1";
        overlayEdit.style.transition = "0.5s";
      }, 1);
    });
  });

  // --- CANCELAR EDITAR ---
  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.opacity = "0";
    overlayEdit.style.transition = "0.5s";
    setTimeout(() => {
      overlayEdit.style.display = "none";
    }, 500);
  });

  // --- SUBMIT DEL FORMULARIO ---
  const formUpdate = document.getElementById("form-update-inasistencia");
  if (formUpdate) {
    formUpdate.addEventListener("submit", async (e) => {
      e.preventDefault();

      const fechaInput = document.getElementById("fecha_edit_inasistencia").value;
      const profesorInput = document.getElementById("profesor_edit_inasistencia").value;
      const horarioInput = document.getElementById("horario_edit_inasistencia").value;

      // Validaciones básicas
      if (!verificarString(profesorInput, "CI Profesor")) return;
      if (!verificarString(horarioInput, "Horario")) return;
      if (!fechaInput) {
        alerta_fallo("Debe seleccionar una fecha válida");
        return;
      }

      const fd = new FormData(e.target);

      try {
        const res = await fetch("/backend/functions/inasistencias/edit.php", {
          method: "POST",
          body: fd,
          credentials: "same-origin"
        });

        const data = await res.json();
        const mensaje = data.message;

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