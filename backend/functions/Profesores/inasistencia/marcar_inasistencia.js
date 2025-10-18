import { alerta_fallo, sw_exito } from '../../../../frontend/js/swalerts.js';
import { crear_selects_horarios } from '../../dependencias/crear_campos.js';

const dia_falta = document.getElementById("dia_falta");
const cantidad_horas_falta = document.getElementById("cantidad_horas_falta");
const formulario_falta = document.querySelector(".inasistencia-form");

// Variable global para almacenar los horarios obtenidos
let horariosDisponibles = [];

// Event listeners
dia_falta.addEventListener("input", cargar_horarios_dia);
cantidad_horas_falta.addEventListener("input", generar_campos_horarios);
formulario_falta.addEventListener("submit", registrar_falta);

// Funcion que hace el fetch cuando cambia el día
async function cargar_horarios_dia() {
    try {
        const dia_falta_valor = document.getElementById("dia_falta").value;
        const contenedor_horas_falta = document.getElementById("horas_falta");
        
        contenedor_horas_falta.style.visibility = "hidden";

        const [año, mes, dia] = dia_falta_valor.split('-').map(Number);
        const fecha_seleccionada = new Date(año, mes - 1, dia);
        let dia_semana_seleccionada = fecha_seleccionada.getDay();
        
        if (dia_semana_seleccionada === 0) {
            alerta_fallo("No se pueden registrar inasistencias los domingos");
            return;
        }
        if (dia_semana_seleccionada === 6) {
            alerta_fallo("No se pueden registrar inasistencias los sábados");
            return;
        }
        
        dia_semana_seleccionada = dia_semana_seleccionada - 1;

        const ahora = new Date();
        const fecha_actual = ahora.toISOString().split('T')[0];

        if(dia_falta_valor < fecha_actual) {
            alerta_fallo("El dia seleccionado no es valido");
            return;
        }

        const respuesta = await fetch("../../backend/functions/Profesores/inasistencia/cargar_horarios.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `fecha=${encodeURIComponent(dia_semana_seleccionada)}`
        });
            
        const data = await respuesta.json();

        horariosDisponibles = data.horarios || [];

        if (data.estado === '1' && horariosDisponibles.length > 0) {
            contenedor_horas_falta.style.visibility = "visible";
            
            const cantidad_horas = document.getElementById("cantidad_horas_falta").value;
            if (cantidad_horas > 0) {
                generar_campos_horarios();
            }
        } else {
            alerta_fallo(data.mensaje || "No hay horarios disponibles para este día");
        }

    } catch (error) {
        console.error('Error:', error);
        alerta_fallo('Error al cargar los horarios');
    }
}

// Funcion que genera los campos cuando cambia cantidad_horas_falta
function generar_campos_horarios() {
    const cantidad_horas = document.getElementById("cantidad_horas_falta").value;
    const contenedor = document.getElementById("campos-dinamicos");
    
    // Limpiar campos anteriores
    contenedor.innerHTML = '';
    
    // Validar que haya horarios disponibles
    if (horariosDisponibles.length === 0) {
        alerta_fallo("Primero selecciona un día para cargar los horarios");
        return;
    }
    
    // Validar cantidad de horas
    if (cantidad_horas <= 0) {
        return;
    }
    
    // Crear los selects con los horarios
    crear_selects_horarios(contenedor, horariosDisponibles, cantidad_horas);
}

async function registrar_falta(e){
    e.preventDefault();

    let dia_faltar = document.getElementById("dia_falta").value;

    // Solo calculamos el día de la semana para enviarlo al PHP
    const [año, mes, dia] = dia_faltar.split('-').map(Number);
    let dia_faltar_profe = new Date(año, mes - 1, dia);
    let dia_semana_seleccionada = dia_faltar_profe.getDay();
    
    if (dia_semana_seleccionada === 0 || dia_semana_seleccionada === 6) {
        alerta_fallo("No se pueden registrar inasistencias los fines de semana");
        return;
    }
    dia_semana_seleccionada = dia_semana_seleccionada - 1;

    let horas_faltar = document.getElementsByName("hora_profesor_da_clase[]");
    let horas_faltadas = [];
    
    for(let select of horas_faltar) {
        if(select.value) {
            horas_faltadas.push(select.value);
        }
    }
    
    if(horas_faltadas.length === 0) {
        alerta_fallo("Debes seleccionar al menos un horario");
        return;
    }

    const form_falta = new FormData();
    form_falta.append("dia_falta", dia_faltar);

    horas_faltadas.forEach(hora => {
        form_falta.append('hora_profesor_da_clase[]', hora);
    });

    form_falta.append("dia_semana_seleccionada", dia_semana_seleccionada);
    form_falta.append("registrarFalta", true);

    let respuesta = await fetch("../../backend/functions/Profesores/inasistencia/reg_inasistencia_api.php", 
        {
            method: "POST",
            body: form_falta
        }
    );

    let data = await respuesta.json();

    if (data.estado === 1){
        sw_exito(`${data.mensaje}`);
    } else {
        alerta_fallo(`${data.mensaje}`);
    }
}