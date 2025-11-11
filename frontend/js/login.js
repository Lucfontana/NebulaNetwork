import { verificarCI, verificarExistenciaCI, mostrarDigVerificador, CIaArreglo } from './validarCI.js';
import { alerta_fallo, sw_redirect } from './swalerts.js';

const form = document.getElementById("form");

// arreglo a utilizarse para el algoritmo de la CI
let multiplicadorACadaUno = [2, 9, 8, 7, 6, 3, 4];

const cedulaInput = document.getElementById("CI");
const contrasenaInput = document.getElementById("contrasena");

if (cedulaInput && contrasenaInput){
    cedulaInput.addEventListener("input", function () {
        CI.value = CI.value.replace(/[^0-9]/g, '').slice(0, 8); 
});
}

form.addEventListener("submit", function(event) {

event.preventDefault();

const CI = String(document.getElementById("CI").value);
const contrasena = String(document.getElementById("contrasena").value);

if (!verificarCI(CI)) {
    alerta_fallo(t("error_invalid_ci"));
    return;
}  else if (!tamanoPassword(contrasena)) {
    alerta_fallo(t("error_invalid_password_length"));
    return;
}  else if (!verificarExistenciaCI(CI, multiplicadorACadaUno)) {
    alerta_fallo(t("error_ci_not_exist"));
    return;
} else {
    //se crea un objeto para tomar los valores del formulario (aca se pondrian todos los datos con .append)
    const formData = new FormData();
                    //id del campo      valor a pasarle
    formData.append('CI', CI);
    formData.append('contrasena', contrasena);
    formData.append('loginUsuario', true);

    // se le pasa al fetch el endpoint que genera la consulta de busqueda, se pone la direccion del php
    fetch('../../backend/login/login_usrs.php', {
        method: 'POST',
        body: formData
    })

    //se toma la respuesta y se devuelve en formato json
    .then(response => response.json())
    //la variable data se usa para recorrer el array asociativo del endpoint...
    .then(data => {
        //si el enpoint devuelve 1...
        if (data.estado === 1) {
            sw_redirect(`${data.mensaje}`, "index.php");
        } else {
            alerta_fallo(`${data.mensaje}`);
        }
    })
}
})
function tamanoPassword (contrasena) {
    if ( contrasena.length < 8 || contrasena.length > 20) {
        return false;
    } else {
        return true;
    }
}