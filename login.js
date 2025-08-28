const form = document.getElementById("form");

let multiplicadorACadaUno = [2, 9, 8, 7, 6, 3, 4];

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
}  else if (!verificarExistenciaCI(CI, multiplicadorACadaUno)) {
    alerttext.textContent = "Su cedula de identidad no existe";
    alertdiv.style.visibility = "visible";
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

function verificarExistenciaCI(CI, multiplicadorACadaUno){

    let digitos_CI_en_int = CIaArreglo(CI);
    let digitoVerif = mostrarDigVerificador(CI, multiplicadorACadaUno)

    if (digitos_CI_en_int[7] == digitoVerif) {
        return true;
    } else {
        return false;
    }
}

function mostrarDigVerificador(CI, multiplicadorACadaUno){
    let digitoVerif;
    let resultadoFinal = 0;
    let restador = 0
    let resultadoActual = 0;

    let digitos_CI_en_int = CIaArreglo(CI);

    for (let i = 0; i < 7; i++){
        resultadoActual = digitos_CI_en_int[i] * multiplicadorACadaUno[i];
        
        resultadoFinal = resultadoFinal + resultadoActual;
        console.log('Final' + resultadoFinal);
    }

    for (let i = 0; restador < resultadoFinal; i++){
        restador = 10 * i;
        console.log('Restador: ' + restador);
    }

    digitoVerif = restador - resultadoFinal;
    console.log('Digito verificador: ' + digitoVerif );
    return digitoVerif;
}

function CIaArreglo(CI){
    let cadenaCI = CI.toString();
    let digitosCIDivididos = cadenaCI.split('');
    let digitos_CI_en_int = digitosCIDivididos.map(Number);
    return digitos_CI_en_int;
}