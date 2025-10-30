import { verificarString, alerta_success_update, alerta_fallo } from './prueba.js';

document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-reserva");
  const btnCancelar = document.getElementById("cancelar-reserva");
  const btnConfirmar = document.getElementById("confirmar-reserva");

  let currentId = null;

  // --- ABRIR MODAL ELIMINAR (solo inasistencias) ---
  document.querySelectorAll(".boton-eliminar-reserva").forEach(boton => {
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
      window.location.href = `/backend/functions/reserva_espacio/delete.php?id=${currentId}`;
    }
  });
});