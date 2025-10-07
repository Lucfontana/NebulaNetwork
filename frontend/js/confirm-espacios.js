import {  } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay");
  const btnCancelar = document.getElementById("cancelar");
  const btnConfirmar = document.getElementById("confirmar");
  const overlayEdit = document.getElementById("overlay-edit");
  const btnCancelarEdit = document.getElementById("cancelarEdit");

  let editID = null;
  let nombre = null;
  let capacidad = null;
  let tipo = null;

  
  let currentId = null;


  // Abrir modal y guardar id
  document.querySelectorAll(".boton-datos-eliminar").forEach(boton => {
    boton.addEventListener("click", (e) => {
      e.preventDefault();
      currentId = boton.dataset.id;
      overlay.style.display = "flex";
    });
  });

  // Cancelar
  btnCancelar.addEventListener("click", () => {
    overlay.style.display = "none";
    currentId = null;
  });

  // Confirmar: redirigir a tu PHP de borrado
  btnConfirmar.addEventListener("click", () => {
    if (currentId) {
      window.location.href = `/backend/functions/Espacios/delete.php?id=${currentId}`;
    }
  });

  document.querySelectorAll(".boton-datos-editar").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();

      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      nombre = botonEditar.dataset.nombre;
      tipo = botonEditar.dataset.tipo;
      capacidad = botonEditar.dataset.capacidad;

      document.getElementById("id_edit").value = editID;
      document.getElementById("name_edit").value = nombre;
      document.getElementById("capacidad_edit").value = capacidad;
      document.getElementById("tipo_edit").value = tipo;
    })
  })

  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.display = "none";
    editID = null;
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
      const res = await fetch("/backend/functions/Espacios/edit.php", {
        method: "POST",
        body: fd,
        credentials: "same-origin"
      });

      const data = await res.json();
      let mensaje = data.message;

      if (data.success) {
        alerta_success_update(mensaje, "/frontend/Espacios.php");
      } else {
        alerta_fallo(mensaje);
      }
    } catch (err) {
      console.error(err);
      alerta_fallo("Error de conexión");
    }
  });
});

  


