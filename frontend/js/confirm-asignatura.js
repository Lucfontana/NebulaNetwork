import { verificarString, alerta_success_update, alerta_fallo } from './prueba.js';
document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay");
  const btnCancelar = document.getElementById("cancelar");
  const btnConfirmar = document.getElementById("confirmar");
  const overlayEdit = document.getElementById("overlay-edit");
  const btnCancelarEdit = document.getElementById("cancelarEdit");
  
  let editID = null;
  let currentId = null;
  let nombre = null;
  

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
      window.location.href = `/backend/functions/asignaturas/delete.php?id=${currentId}`;
    }
  });

  document.querySelectorAll(".boton-datos-editar").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();

      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      nombre = botonEditar.dataset.nombre;

      document.getElementById("id_edit").value = editID;
      document.getElementById("name_edit").value = nombre;

    })
  })

  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.display = "none";
    editID = null;
  });

  document.getElementById("form-update").addEventListener("submit", async (e) => {
      e.preventDefault(); // Evita el submit normal
        
      let nombreInput = document.getElementById("name_edit").value;
  
      // Validaciones
      if (!verificarString(nombreInput, "Nombre")) {
        return;
      }
      
  
      // Si pasa las validaciones, envía el formulario
      const form = e.target;
      const fd = new FormData(form);
  
      try {
        const res = await fetch("/backend/functions/asignaturas/edit.php", {
          method: "POST",
          body: fd,
          credentials: "same-origin"
        });
  
        const data = await res.json();
        let mensaje = data.message;
  
        if (data.success) {
          alerta_success_update(mensaje, "/frontend/asignaturas.php");
        } else {
          alerta_fallo(mensaje);
        }
      } catch (err) {
        console.error(err);
        alerta_fallo("Error de conexión");
      }
    });

});


