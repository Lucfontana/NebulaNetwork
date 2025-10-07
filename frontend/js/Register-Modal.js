const botones = document.querySelectorAll(".btn");
const dialogs = document.querySelectorAll("dialog");
const closeBoton = document.querySelectorAll(".btn-Cerrar");
const closeConfirm = document.querySelectorAll(".btn-enviar-registro");
const inputs = document.querySelectorAll(".input-register");
const body = document.getElementById("body-register");

// Evita que el fondo se desplace cuando el modal está abierto

botones.forEach((boton, index) => {
  boton.addEventListener("click", function () {
    dialogs[index].showModal(); // Muestra el dialog correspondiente al botón
    dialogs[index].style.transform = "translateY(5%)";
    dialogs[index].style.opacity = "1";
    dialogs[index].style.transition = "0.5s";
    body.style.overflow = "hidden"; // Evita el desplazamiento del fondo
    inputs.forEach(input => {
      input.value = ""; // Limpia el valor del input
    });
  });
})

closeBoton.forEach((botonCerrar, index) => {
  botonCerrar.addEventListener("click", function () {
    dialogs[index].style.transform = "translateY(-50%)";
    dialogs[index].style.opacity = "0";
    dialogs[index].style.transition = "0.5s";
    body.style.overflow = "visible"; // Evita el desplazamiento del fondo

    setTimeout(() => {
      dialogs[index].close(); // Cierra el dialog correspondiente
    }, 500)
  });
});

closeConfirm.forEach((confirmCerrar, index) => {
  confirmCerrar.addEventListener("click", function () {
    dialogs[index].style.transform = "translateY(-50%)";
    dialogs[index].style.opacity = "0";
    dialogs[index].style.transition = "0.5s";
    body.style.overflow = "visible"; // Evita el desplazamiento del fondo

    setTimeout(() => {
      dialogs[index].close(); // Cierra el dialog correspondiente
    }, 500)
  });
});




