import { alerta_fallo } from '../../../../frontend/js/swalerts.js';

const dia_falta = document.getElementById("dia_falta");
const formulario_falta = document.querySelector(".inasistencia-form");

dia_falta.addEventListener("input", cargar_horarios_dia);
// formulario_falta.addEventListener("submit", registrar_falta);

async function cargar_horarios_dia() {
    console.log("ta cambiando");
    try {
        const dialogs = document.getElementById("dialogs");
        const dia_falta_valor = document.getElementById("dia_falta").value;

        const diaValido = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'].includes(dia_falta_valor);
        if(!diaValido) {
            alerta_fallo("El dia seleccionado no es valido");
            return;
        }

        const respuesta = await fetch("../../backend/functions/Profesores/inasistencia/cargar_horarios.php");
        const data = await respuesta.json();

        // CORRECCIÓN PRINCIPAL: quitar los paréntesis
        let horarios = data.horarios;

        // Buscar si ya existe el contenedor
        let campoDiv = dialogs.querySelector('.div-labels');
        if (!campoDiv) {
            // Si no existe, crearlo
            campoDiv = document.createElement('div');
            campoDiv.className = 'div-labels';
            
            let label = document.createElement('label');
            label.textContent = 'Hora a inasistir';
            label.setAttribute('for', 'hora_inasistencia');
            label.className = 'label';
            
            campoDiv.appendChild(label);
            dialogs.appendChild(campoDiv);
        }

        // Buscar o crear el select
        let select = document.getElementById('hora_inasistencia');
        if (!select) {
            select = document.createElement('select');
            select.className = 'input-register';
            select.id = 'hora_inasistencia';
            select.name = 'hora_inasistencia';
            select.required = true;
            campoDiv.appendChild(select);
        }

        // Limpiar opciones anteriores
        select.innerHTML = '';

        // Agregar opción predeterminada
        let opcionPredeterminada = document.createElement("option");
        opcionPredeterminada.value = '';
        opcionPredeterminada.textContent = 'Selecciona una opción';
        opcionPredeterminada.disabled = true;
        opcionPredeterminada.selected = true;
        select.appendChild(opcionPredeterminada);
        
        // Agregar horarios
        horarios.forEach(horario => {
            let opcion = document.createElement("option");
            opcion.value = horario.id_horario;
            opcion.textContent = `${horario.hora_inicio} - ${horario.hora_final}`;
            select.appendChild(opcion);
        });

    } catch (error) {
        console.error('Error completo:', error);
        alerta_fallo('Error al cargar los horarios');
    }
}