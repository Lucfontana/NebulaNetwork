import { verificarNombreEspecial } from './validaciones-registro';
import { verificarCapacidad } from './validaciones-registro';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay");
  const btnCancelar = document.getElementById("cancelar");
  const btnConfirmar = document.getElementById("confirmar");
  const overlayEdit = document.getElementById("overlay-edit");
  const btnCancelarEdit = document.getElementById("cancelarEdit");


  let editID = null;
  let nombre = null;
  let cupos = null;
  let capacidad = null;
  
  let currentId = null;

  document.querySelectorAll(".actualizar").forEach(btnActu => {
     btnActu.addEventListener("click", (e) => {
      const nombreInput = document.getElementById("name_edit").value;
      const capacidadInput = document.getElementById("capacidad_edit").value;

      if (!verificarNombreEspecial(nombreInput)) {
        e.preventDefault();
      }
      if (!verificarCapacidad(capacidadInput)) {
        e.preventDefault();
      }
    })
  })

     
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
      window.location.href = `/backend/functions/Cursos/delete.php?id=${currentId}`;
    }
  });

  document.querySelectorAll(".boton-datos-editar").forEach(botonEditar => {
    botonEditar.addEventListener("click", (a) => {
      a.preventDefault();

      overlayEdit.style.display = "flex";

      editID = botonEditar.dataset.id;
      nombre = botonEditar.dataset.nombre;
      cupos = botonEditar.dataset.cupo;
      capacidad = botonEditar.dataset.capacidad;

      document.getElementById("id_edit").value = editID;
      document.getElementById("name_edit").value = nombre;
      document.getElementById("capacidad_edit").value = capacidad;
      document.getElementById("cupos_edit").value = cupos;

      
    })
  })

  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.display = "none";
    editID = null;
  });

});


