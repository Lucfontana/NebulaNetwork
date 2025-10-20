// Modal Change Password
const changePasswd = document.getElementById('change-passwd');
const dialogChangePasswd = document.getElementById('dialog-change-passwd');
const closeChangePasswd = document.getElementById('cancelarEdit');
const confirmarpasswd = document.getElementById('confirmarpasswd');
const btnActualizar = document.getElementById("confirmar");

changePasswd.addEventListener("click", function (a) {
    a.preventDefault();
    dialogChangePasswd.style.display = "flex";
    dialogChangePasswd.style.opacity = "1";
    dialogChangePasswd.style.transition = "0.5s";
});

confirmarpasswd.addEventListener("click", (e) => {
    const passwd = document.getElementById("passwd").value;
    const newpasswd = document.getElementById("newpasswd").value;

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
    dialogChangePasswd.style.display = "none";
});

document.getElementById("comprobarcontraseña").addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita recargar la página
    
  const form = e.target;
  const fd = new FormData(form);

  try {
    const res = await fetch("../../backend/functions/edit-paswd-user.php", {
      method: "POST",
      body: fd,
      credentials: "same-origin"
    });

    const data = await res.json();
    mensaje= data.message;


    if (data.success) {
      // redirigir después de 1 segundo
      alerta_success(mensaje, "../frontend/Perfil.php");
    } else {
      alerta_fallo(mensaje);
    }
  } catch (err) {
    console.error(err);
    mensaje.textContent = "Error de conexión";
  }
});

// Sweet Alert 

function alerta_success(mensaje, ventana_a_redirigir){
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

function alerta_fallo(mensaje){
            Swal.fire({
                title: "Ups...",
                text: mensaje,
                icon: "error"
            });

}

