// Modal Change Password
const changePasswd = document.getElementById('change-passwd');
const dialogChangePasswd = document.getElementById('dialog-change-passwd');
const closeChangePasswd = document.getElementById('cancelarEdit');
const confirmarpasswd = document.getElementById('confirmarpasswd');
const btnActualizar = document.getElementById("confirmar");

const mensaje = document.getElementById("mensajeContraseña");



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
        mensaje.textContent = "Por favor, complete todos los campos.";
        e.preventDefault();
        return;
    } else if (passwd === newpasswd) {
        mensaje.textContent = "La nueva contraseña debe ser diferente a la actual.";
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
    mensaje.textContent = data.message;

    if (data.success) {
      // redirigir después de 1 segundo
      setTimeout(() => window.location.href = "../frontend/Perfil.php", 1000);
    }
  } catch (err) {
    console.error(err);
    mensaje.textContent = "Error de conexión";
  }
});



