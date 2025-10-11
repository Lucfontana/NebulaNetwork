import { verificarNombreEspecial, verificarCapacidad, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay");
  const btnCancelar = document.getElementById("cancelar");
  const btnConfirmar = document.getElementById("confirmar");
  const overlayEdit = document.getElementById("overlay-edit");
  const btnCancelarEdit = document.getElementById("cancelarEdit");

  let editID = null;
  let nombre = null;
  let capacidad = null;
  let currentId = null;

  // Abrir modal eliminar
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

  btnCancelar.addEventListener("click", () => {
    overlay.style.opacity = "0";
    overlay.style.transition = "0.5s";
    currentId = null;
    setTimeout(() => {
       overlay.style.display = "none"; 
    }, 500)
  });

  btnConfirmar.addEventListener("click", () => {
    if (currentId) {
      window.location.href = `/backend/functions/Cursos/delete.php?id=${currentId}`;
    }
  });

  // Abrir modal editar
  document.querySelectorAll(".boton-datos-editar").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();
      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      nombre = botonEditar.dataset.nombre;
      capacidad = botonEditar.dataset.capacidad;

      document.getElementById("id_edit").value = editID;
      document.getElementById("name_edit").value = nombre;
      document.getElementById("capacidad_edit").value = capacidad;

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
    let capacidadInput = document.getElementById("capacidad_edit").value;

    // Validaciones
    if (!verificarNombreEspecial(nombreInput)) {
      return;
    }
    if (!verificarCapacidad(capacidadInput)) {
      return;
    }

    // Si pasa las validaciones, envía el formulario
    const form = e.target;
    const fd = new FormData(form);

    try {
      const res = await fetch("/backend/functions/Cursos/edit.php", {
        method: "POST",
        body: fd,
        credentials: "same-origin"
      });

      const data = await res.json();
      let mensaje = data.message;

      if (data.success) {
        alerta_success_update(mensaje, "/frontend/Cursos.php");
      } else {
        alerta_fallo(mensaje);
      }
    } catch (err) {
      console.error(err);
      alerta_fallo("Error de conexión");
    }
  });

});