import { verificarString, alerta_success_update, alerta_fallo } from './prueba.js';
document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-orientacion");
  const btnCancelar = document.getElementById("cancelar-orientacion");
  const btnConfirmar = document.getElementById("confirmar-orientacion");
  const overlayEdit = document.getElementById("overlay-edit-orientacion");
  const btnCancelarEdit = document.getElementById("cancelarEdit-orientacion");
  
  let editID = null;
  let currentId = null;
  let nombre = null;
  

  // Abrir modal y guardar id
  document.querySelectorAll(".boton-eliminar-orientacion").forEach(boton => {
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
      window.location.href = `/backend/functions/orientacion/delete.php?id=${currentId}`;
    }
  });

  document.querySelectorAll(".boton-editar-orientacion").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();

      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      nombre = botonEditar.dataset.nombre;

      document.getElementById("id_edit_orientacion").value = editID;
      document.getElementById("name_edit_orientacion").value = nombre;

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

  document.getElementById("form-update-orientacion").addEventListener("submit", async (e) => {
      e.preventDefault(); // Evita el submit normal
        
      let nombreInput = document.getElementById("name_edit_orientacion").value;
  
      // Validaciones
      if (!verificarString(nombreInput, "Nombre")) {
        return;
      }
      
  
      // Si pasa las validaciones, envía el formulario
      const form = e.target;
      const fd = new FormData(form);
  
      try {
        const res = await fetch("/backend/functions/orientacion/edit.php", {
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


