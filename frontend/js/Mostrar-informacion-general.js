const titulo = document.getElementById("titulo-mostrar-informacion"); //Titulo predeterminado cuando no se muestra nada
const botonMostrar = document.querySelectorAll(".mostrar-informacion-oculta"); //boton para abrir el contenedor de mostrar info en celu (todo el contenedor)
const iconoMostrar = document.querySelectorAll(".mostrar-informacion-icono"); // 
const iconoGuardar = document.querySelectorAll(".guardar-informacion-icono")
const informacionEscondida = document.querySelectorAll(".informacion-escondida");
const selectInformacion = document.getElementById('informacion-change'); //En mostrar_informacion.php

//creamos una variable para establecer si hay que abrirlo o cerrarlo
let variable = 0;
botonMostrar.forEach((mostrar, index) => {
    mostrar.addEventListener("click", function () {
        if (variable === 0) { //Si al apretar el botonMostrar, la variable es 0 (significa contenido escondido)...
            informacionEscondida[index].style.display = "flex"; //Se muestra la info escondida
            informacionEscondida[index].style.transition = "1s";
            iconoMostrar[index].style.display = "none"; //Se esconde el boton de mostrar
            iconoGuardar[index].style.display = "flex"; //Se muestra el icono para guardar

            //Se cambia la opacidad para que la informacion escondida, ahora sea visible
            setTimeout(() => {
                informacionEscondida[index].style.transform = "translateY(0%)"; //Para que aparezca en el lugar correcto
                informacionEscondida[index].style.opacity = "1";
                informacionEscondida[index].style.transition = "0.5s";
            }, 0.1)

            variable = 1;

        } else { //Si la variable llega a ser 1 (o cuaqlquier otro), se esconde todo
            informacionEscondida[index].style.transform = "translateY(-100%)"; //Animacion de q se va para arriba
            informacionEscondida[index].style.opacity = "0"; //Opacidad cambia a 0 para que su contenido no se vea
            informacionEscondida[index].style.transition = "0.5s";
            iconoGuardar[index].style.display = "none";
            iconoMostrar[index].style.display = "flex"; //Aparece iconoMostrar y se esconde el de guardar

            //Despues de la trancision, se esconde el contenedor
            setTimeout(() => {
                informacionEscondida[index].style.display = "none"; //Se esconde el contenedor por completo
            }, 500)

            variable = 0;
        }
    });
});

// Función para mostrar la sección correspondiente
function mostrarSeccion(valor) {
    titulo.style.display = "none"; //El titulo predeterminado se esconde
    document.querySelectorAll('.seccion-oculta').forEach(seccion => { //Todas las secciones ocultas se esconden
        seccion.style.display = 'none';
    });

    if (valor == "0") {
        titulo.style.display = "flex"; //Si el valor es 0 significa que no se eligio nada para 
                                       //ver, por lo que se muestra el titulo predeterminado
    } else if (valor) { //Con cualquier otro valor, se seleccionala seccion que tenga el nombre de ese valor
        const seccionSeleccionada = document.querySelector(`[data-seccion="${valor}"]`);
        if (seccionSeleccionada) { //Si existe la seccionSeleccionada, se muestra esa misma
            seccionSeleccionada.style.display = 'flex';
        }
    }
}

// Cuando el selectInformacion cambia, se ejecuta una funcion que guarda ese valor en el localStorage
selectInformacion.addEventListener('change', function () {
    const valor = this.value; //Trae el valor de selectInformation (se lo llama con this pq,
                              //como el eventlistener se aplica en selectInformacion, se entiende
                              //que queres traer el valor del elemento con el que estas trabajando)
                              //En funciones de flecha esto no funciona asi, asi q hay que tener cuidado (event.target.value)
    //Se guarda en localStorage el valor con el nombre 'seccionSeleccionada'
    localStorage.setItem('seccionSeleccionada', valor);

    //Mostrar la sección
    mostrarSeccion(valor);
});

// Al cargar la página, restaurar el valor guardado
document.addEventListener('DOMContentLoaded', function () {
    // Ocultar todas las secciones inicialmente
    document.querySelectorAll('.seccion-oculta').forEach(seccion => {
        seccion.style.display = 'none';
    });

    // Recuperar el valor guardado en localStorage
    const valorGuardado = localStorage.getItem('seccionSeleccionada');

    if (valorGuardado) { //Si hau un valor guardado, se lo muestra, si no mostraria el predeterminado
        // Establecer el valor del select
        selectInformacion.value = valorGuardado;

        // Mostrar la sección correspondiente
        mostrarSeccion(valorGuardado);
    }
});