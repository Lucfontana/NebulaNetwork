// Modal Change Password
const changePasswd = document.getElementById('change-passwd');
const dialogChangePasswd = document.getElementById('dialog-change-passwd');
const closeChangePasswd = document.getElementById('cancelarEdit');

changePasswd.addEventListener("click", function (e) {
  e.preventDefault();
  dialogChangePasswd.style.display = "flex";
  setTimeout(() => {
    dialogChangePasswd.style.opacity = "1";
    dialogChangePasswd.style.transition = "0.5s";
  }, 1);
});


closeChangePasswd.addEventListener("click", () => {
  dialogChangePasswd.style.opacity = "0";
  dialogChangePasswd.style.transition = "0.5s";
  setTimeout(() => {
    dialogChangePasswd.style.display = "none";
  }, 500);
});


// Formulario de cambio de contraseña
const formChangePasswd = document.getElementById("comprobarcontraseña");

  formChangePasswd.addEventListener("submit", async (e) => {
    e.preventDefault();

    const passwd = document.getElementById("passwd").value.trim();
    const newpasswd = document.getElementById("newpasswd").value.trim();

    // Validaciones en el usuario
    if (!passwd || !newpasswd) {
      alerta_fallo("Por favor, complete todos los campos.");
      return;
    }

    if (passwd === newpasswd) {
      alerta_fallo("La nueva contraseña debe ser diferente a la actual.");
      return;
    }

    if (newpasswd.length < 8) {
      alerta_fallo("La nueva contraseña debe tener al menos 8 caracteres.");
      return;
    }

    // Crear FormData
    const fd = new FormData(e.target);

    try {
      const res = await fetch("/backend/functions/edit-paswd-user.php", {
        method: "POST", 
        body: fd,
        credentials: "same-origin"
      });

      // Verificar si la respuesta es JSON válida
      const contentType = res.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        throw new Error("La respuesta no es JSON válida");
      }

      // Espera Respuesta de edit-paswd-user
      const data = await res.json();

      // Declarar correctamente la variable mensaje
      const mensaje = data.message || "Sin mensaje del servidor";

      if (data.success) {
        alerta_success(mensaje, "/frontend/Perfil.php");
      } else {
        alerta_fallo(mensaje);
      }

    } catch (err) {
      console.error("Error completo:", err);
      alerta_fallo("Error de conexión con el servidor");
    }
  });


// Sweet Alerts
function alerta_success(mensaje, ventana_a_redirigir) {
  Swal.fire({
    title: "¡Éxito!",
    text: mensaje,
    icon: "success",
    confirmButtonColor: "#3085d6",
    confirmButtonText: "Ok",
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