// Modal Change Password
const changePasswd = document.getElementById('change-passwd');
const dialogChangePasswd = document.getElementById('dialog-change-passwd');
const closeChangePasswd = document.getElementById('cancelarEdit');
const confirmarpasswd = document.getElementById('confirmarpasswd');
const btnActualizar = document.getElementById("confirmar");

changePasswd.addEventListener("click", function (a) {
  a.preventDefault();
  dialogChangePasswd.style.display = "flex";
  setTimeout(() => {
    dialogChangePasswd.style.opacity = "1";
    dialogChangePasswd.style.transition = "0.5s";
  }, 1)

  //Pequeña animación al abrir
});

confirmarpasswd.addEventListener("click", (e) => {
  const passwd = document.getElementById("passwd").value;
  const newpasswd = document.getElementById("newpasswd").value;
  //Guardamos el contenido de los inputs en constantes y validamos
  if (passwd === "" || newpasswd === "") {
    alerta_fallo("Por favor, complete todos los campos.");
    e.preventDefault();
    return;
  } else if (passwd === newpasswd) {
    alerta_fallo("La nueva contraseña debe ser diferente a la actual.");
    e.preventDefault();
    return;
  } else if (newpasswd.length < 8) {
    alerta_fallo("La nueva contraseña debe ser de al menos 8 caracteres.");
    e.preventDefault();
    return;
  }

});

closeChangePasswd.addEventListener("click", () => {
  dialogChangePasswd.style.opacity = "0";
  dialogChangePasswd.style.transition = "0.5s";
  setTimeout(() => {
    dialogChangePasswd.style.display = "none";
  }, 500)
});

document.getElementById("comprobarcontraseña").addEventListener("submit", async (e) => {
  e.preventDefault(); // Evita recargar la página


  //hace referencia al elemento que ejecuto el evento, en este caso el formulario comprobarcontraseña
  const form = e.target;
  //permite acceder al contenido del form
  const fd = new FormData(form);


  //evita errores - los encapsula
  try {
    const res = await fetch("../../backend/functions/edit-paswd-user.php", {
      method: "POST", //establece el metodo post
      body: fd, //Enviar los datos necesarios
      credentials: "same-origin"
    });

    // Espero recibir la respuesta del edit-paswd_user.php en json
    const data = await res.json();
    //cargamos el mensaje en una variable
    mensaje = data.message;

    //si el mensaje json es correcto entonces se ejecuta el alerta_success
    if (data.success) {
      // redirigir después de 1 segundo
      alerta_success(mensaje, "../frontend/Perfil.php");
    } else {
      alerta_fallo(mensaje);
    }
  } catch (err) {
    console.error(err);
    mensaje.textContent = "Error de conexión"; //si el try tiene error va a salir este mensaje
  }
});

// Sweet Alert 

function alerta_success(mensaje, ventana_a_redirigir) {
  Swal.fire({
    title: "Exito!",
    text: mensaje,
    icon: "success",
    cancelButtonColor: "#3085d6",
    cancelButtonText: "OK"
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = ventana_a_redirigir;
    }
  });
}

function alerta_fallo(mensaje) {
  Swal.fire({
    title: "Ups...",
    text: mensaje,
    icon: "error"
  });

}

