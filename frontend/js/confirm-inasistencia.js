document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("overlay-inasistencia");
  const btnCancelar = document.getElementById("cancelar-inasistencia");
  const btnConfirmar = document.getElementById("confirmar-inasistencia");

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
});