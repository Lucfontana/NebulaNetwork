const botones = document.querySelectorAll(".btn");
const closeBoton = document.querySelectorAll(".btn-Cerrar");
const overlays = document.querySelectorAll(".overlay");
const body = document.getElementById("body-register");

// Evita que el fondo se desplace cuando el modal está abierto

//Cada boton abre su respectivo modal, ya que son arrays
                      //index indica la posicion del array que son (como tenemos muchos botones con la clase btn,
                      //devuelve un arreglo con cada uno, por lo que el index indica QUE BOTON corresponde a QUE OVERLAY)
botones.forEach((boton, index) => {
  boton.addEventListener("click", function () {
    overlays[index].style.display = "flex"; // Muestra el overlay
    body.style.overflow = "hidden"; //Evita que se pueda hacer scroll afuera del modal

    //Despues de que el elemento se haya mostrado (lo de arriba) se espera UN MICROSEGUNDO
    //(hasta que todo lo de arriba se ejecute bien) y despues se muestra el contenedor con una transicion chiquita
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
    body.style.overflow = "visible"; //Hace que se pueda desplazar el body nuevamente (pq antes lo habiamos evitado)

    //Transicion de medio segundo para acompañar lo de arriba
    setTimeout(() => {
      overlays[index].style.display = "none";
    }, 500)
  });
});






