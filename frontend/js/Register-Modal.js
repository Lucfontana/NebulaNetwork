const botones = document.querySelectorAll(".btn");
const closeBoton = document.querySelectorAll(".btn-Cerrar");
const closeConfirm = document.querySelectorAll(".btn-enviar-registro");
const inputs = document.querySelectorAll(".input-register");
const overlays = document.querySelectorAll(".overlay");
const body = document.getElementById("body-register");

// Evita que el fondo se desplace cuando el modal está abierto


//Cada boton abre su respectivo modal, ya que son arrays
botones.forEach((boton, index) => {
  boton.addEventListener("click", function () {
    overlays[index].style.display = "flex"; // Muestra el overlay
    body.style.overflow = "hidden"; // Evita el desplazamiento del fondo

    setTimeout(() => {
      overlays[index].style.opacity = "1";
      overlays[index].style.transition = "0.5s";
    }, 1)
  });
})

//Cada boton cierra su respectivo modal, ya que son arrays
closeBoton.forEach((botonCerrar, index) => {
  botonCerrar.addEventListener("click", function () {
    overlays[index].style.opacity = "0"; // Muestra el overlay
    overlays[index].style.transition = "0.5s"; // Muestra el overlay
    body.style.overflow = "visible"; // Evita el desplazamiento del fondo

    setTimeout(() => {
      overlays[index].style.display = "none";
    }, 500)
  });
});






