import { verificarString, alerta_success_update, alerta_fallo } from './prueba.js';
//importamos los js necesarios, como alertas o validaciones

//Todo esto se ejecuta en frontend/CRUD/asignaturas.php
document.addEventListener("DOMContentLoaded", () => { //Cuando se carga todo el contenido del DOM, se comienza a ejecutar el codigo
  const overlay = document.getElementById("overlay-asignatura"); //Overlay que pregunta si quiere borrar o no
  const btnCancelar = document.getElementById("cancelar-asignatura"); //Boton para cancelar la eliminacion
  const btnConfirmar = document.getElementById("confirmar-asignatura"); //Boton para confirmar la eliminacion
  const overlayEdit = document.getElementById("overlay-edit-asignatura"); //popup para editar
  const btnCancelarEdit = document.getElementById("cancelarEdit-asignatura"); //Boton de cancelar edicion
  
  let currentId = null;

  // Abrir modal eliminar - SOLO para asignaturas
  document.querySelectorAll(".boton-eliminar-asignatura").forEach(boton => {
    boton.addEventListener("click", (e) => {
      e.preventDefault();
      currentId = boton.dataset.id; //cargamos el id enviado por data en currenId
      overlay.style.display = "flex"; //Se muestra el overlay para borrar
      setTimeout(() => { //Se le aplica una animacion mientras se empieza a mostrar
        overlay.style.opacity = "1";
        overlay.style.transition = "0.5s";
      }, 1);
    });
  });

  // Cancelar eliminar
  btnCancelar.addEventListener("click", () => {
    overlay.style.opacity = "0";
    overlay.style.transition = "0.5s";
    currentId = null; //Se resetea el currentId pq le guardamos un valor antes 
                      //(por si el usuario quiere borrar otro elemento que no sea este)
    setTimeout(() => { //Espera hasta que termine la trancision para esconder completamente el boton,
                       //por que o si no estos botones seguirian en la p'agina pero estarian invisibles ante el usuario
      overlay.style.display = "none"; 
    }, 500);
    //pequeña animación
  });

  // Confirmar eliminar
  btnConfirmar.addEventListener("click", () => {
    // si el currentId recibió correctamente el dataset entonces se ejecutara el el delete.php y se le cargara el id
    if (currentId) {
      window.location.href = `/backend/functions/asignaturas/delete.php?id=${currentId}`;
    }
  });

  // Abrir modal editar - SOLO para asignaturas
  document.querySelectorAll(".boton-editar-asignatura").forEach(botonEditar => {
    botonEditar.addEventListener("click", (e) => {
      e.preventDefault();
      overlayEdit.style.display = "flex";

      const editID = botonEditar.dataset.id;
      const nombre = botonEditar.dataset.nombre;

      document.getElementById("id_edit_asignatura").value = editID;
      document.getElementById("name_edit_asignatura").value = nombre;

      // Se  le cargan los datos a editar en el input para que el usuario sepa que está editando

      setTimeout(() => {
        overlayEdit.style.opacity = "1";
        overlayEdit.style.transition = "0.5s";
      }, 1);
    });
  });

  // Cancelar editar
  btnCancelarEdit.addEventListener("click", () => {
    overlayEdit.style.opacity = "0";
    overlayEdit.style.transition = "0.5s";
    setTimeout(() => {
      overlayEdit.style.display = "none";
    }, 500);
  });

  // Submit del formulario
  const formUpdate = document.getElementById("form-update-asignatura");
  if (formUpdate) {
    formUpdate.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      let nombreInput = document.getElementById("name_edit_asignatura").value;
      // guarda el nombre a editar dentro de la variable y validamos

      if (!verificarString(nombreInput, "Nombre")) {
        return;
      }

      //hace referencia al elemento que ejecuto el evento, en este caso el formulario
      const fd = new FormData(e.target);

      // Si hay errores de conexión le permite enviar un mensaje al usuario
      try {
          const res = await fetch("/backend/functions/asignaturas/edit.php", { //es quien va a procesar la solicitud
          method: "POST", // Envia los datos por meotodo post
          body: fd, // Permite acceder a los datos del formulario
          credentials: "same-origin"
        });


        //espera la respuesta de edit.php
        const data = await res.json();
        //Guarda el mensaje recibido en una variable
        let mensaje = data.message;
        
        // Si el json dio como respuesta un success se ejecuta lo de abajo
        if (data.success) {
          alerta_success_update(mensaje, "/frontend/Mostrar_informacion.php");
        } else {
          alerta_fallo(mensaje);
        }
      //En caso de error se muestra el catch, muestra el error en la consola y le muestra una alerta de fallo al usuario
      } catch (err) {
        console.error(err);
        alerta_fallo("Error de conexión");
      }
    });
  }
});