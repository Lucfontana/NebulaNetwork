import { verificarNombreEspecial, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-recurso");
  const btnCancelar = document.getElementById("cancelar-recurso");
  const btnConfirmar = document.getElementById("confirmar-recurso");
  const overlayEdit = document.getElementById("overlay-edit-recurso");
  const btnCancelarEdit = document.getElementById("cancelarEdit-recurso");

  let editID = null;
  let idespacio = null;
  let nombre = null;
  let tipo = null;
  let estado = null;
  
  let currentId = null;


  // Abrir modal y guardar id
  document.querySelectorAll(".boton-eliminar-recurso").forEach(boton => {
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
      window.location.href = `/backend/functions/Recursos/delete.php?id=${currentId}`;
    }
  });

  document.querySelectorAll(".boton-editar-recurso").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();

      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      idespacio = botonEditar.dataset.espacio;
      nombre = botonEditar.dataset.nombre;
      tipo = botonEditar.dataset.tipo;
      estado = botonEditar.dataset.estado;

      document.getElementById("id_edit_recurso").value = editID;
      document.getElementById("id_espacio_edit").value = idespacio;
      document.getElementById("name_edit_recurso").value = nombre;
      document.getElementById("tipo_edit_recurso").value = tipo;
      document.getElementById("estado_edit_recurso").value = estado;

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
      
    let nombreInput = document.getElementById("name_edit_recurso").value;

    if (!verificarNombreEspecial(nombreInput)) {
        return;
    }
    

    // Validaciones
    

    // Si pasa las validaciones, envía el formulario
    const form = e.target;
    const fd = new FormData(form);

    try {
      const res = await fetch("/backend/functions/Recursos/edit.php", {
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
});


