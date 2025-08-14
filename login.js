const form = document.getElementById("form");

form.addEventListener("submit", function(event) {
event.preventDefault();

const CI = Number(document.getElementById("CI").value);
const contrasena = String(document.getElementById("contrasena").value);
const alertdiv = document.getElementById("alert");
const alerttext = document.getElementById("alert.text");

if (!verificarCI(CI)) {
    alerttext.textContent = "La CI debe ser de 8 digitos, sin puntos, ni guiones.";
    alertdiv.style.visibility = "visible";
    return
}  else if (!tamanoPassword(contrasena)) {
    alerttext.textContent = "Contraseña debe tener entre 8 y 20 caracteres.";
    alertdiv.style.visibility = "visible";
    return
} else {
    alerttext.textContent = "Inicio de Sesión Correcto";
    alertdiv.style.visibility = "visible";
}

})

function verificarCI (CI) {
   if (/^\d{8}$/.test(CI)) {
    return true;
   } else {
    return false;
   }
}

function tamanoPassword (contrasena) {
    if ( contrasena.length < 8 || contrasena.length > 20) {
        return false;
    } else {
        return true;
    }
}