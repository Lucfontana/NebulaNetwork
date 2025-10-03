import { verificarCI, verificarExistenciaCI, mostrarDigVerificador, CIaArreglo } from './validarCI.js';

const form = document.getElementById("form");

// arreglo a utilizarse para el algoritmo de la CI
let multiplicadorACadaUno = [2, 9, 8, 7, 6, 3, 4];

form.addEventListener("submit", function(event) {


const CI = Number(document.getElementById("CI").value);
const contrasena = String(document.getElementById("contrasena").value);
const alertdiv = document.getElementById("alert");
const alerttext = document.getElementById("alert.text");

if (!verificarCI(CI)) {
    alerttext.textContent = "La CI debe ser de 8 digitos, sin puntos, ni guiones.";
    alertdiv.style.visibility = "visible";
    event.preventDefault();
    return
}  else if (!tamanoPassword(contrasena)) {
    alerttext.textContent = "Contraseña debe tener entre 8 y 20 caracteres.";
    alertdiv.style.visibility = "visible";
    event.preventDefault();
    return
}  else if (!verificarExistenciaCI(CI, multiplicadorACadaUno)) {
    alerttext.textContent = "Su cedula de identidad no existe";
    alertdiv.style.visibility = "visible";
    event.preventDefault();
} else {
    alerttext.textContent = "Inicio de Sesión Correcto";
    alertdiv.style.visibility = "visible";
}

})
function tamanoPassword (contrasena) {
    if ( contrasena.length < 8 || contrasena.length > 20) {
        return false;
    } else {
        return true;
    }
}