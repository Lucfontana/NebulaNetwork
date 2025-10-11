import {alerta_fallo, alerta_success} from './validaciones-registro.js';

let campos_a_crear = document.getElementById("crear_campos");

campos_a_crear.addEventListener("change", crear_campos);

function crear_campos(){
    let cantidad_campos = parseInt(document.getElementById("crear_campos").value);
    let contenedor = document.getElementById("campos-dinamicos");

    if (!cantidad_campos || cantidad_campos <= 0 ){
        alerta_fallo("La cantidad de campos debe ser mayor a 0");
        return;
    }
    if (cantidad_campos > 8) {
        alerta_fallo("La cantidad de campos no puede ser mayor a 8");
        return;
    }

    contenedor.innerHTML='';

    for (let i = 1; i <= count; i++ ){
        let campoDiv = document.createElement('div');
        campoDiv.className = 'div-labels';

        let label = document.createElement('label');
        label.textContent = "Hora de la clase " + i + ":";
        label.setAttribute('for', 'hora_clase');
        label.className = 'label';

        let select = document.createElement('select');
        select.className = 'input-register';
        select.id = 'hora_profesor_da_clase';
        select.name = 'hora_profesor_da_clase';
        

    }
}