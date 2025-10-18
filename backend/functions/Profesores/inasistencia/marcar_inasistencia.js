import { alerta_fallo } from '../../../../frontend/js/swalerts.js';
import { crear_selects_horarios } from '../../dependencias/crear_campos.js';

const dia_falta = document.getElementById("dia_falta");
const cantidad_horas_falta = document.getElementById("cantidad_horas_falta");
const formulario_falta = document.querySelector(".inasistencia-form");

// Variable global para almacenar los horarios obtenidos
let horariosDisponibles = [];

// Event listeners
dia_falta.addEventListener("input", cargar_horarios_dia);
cantidad_horas_falta.addEventListener("input", generar_campos_horarios);
// formulario_falta.addEventListener("submit", registrar_falta);

// Funcion que hace el fetch cuando cambia el día
async function cargar_horarios_dia() {
    console.log("============");
    console.log("ta cambiando");
    try {
        const dia_falta_valor = document.getElementById("dia_falta").value;
        const contenedor_horas_falta = document.getElementById("horas_falta");
        
        //Se esconde el boton del contenedor de las horas que 
        //falta por defecto (para despues mostrarlo si todo ta bien)
        contenedor_horas_falta.style.visibility = "hidden";

        // Se pasa como un objeto de hora la fecha seleccionada por el usuario
        const fecha_seleccionada = new Date(dia_falta_valor);

        // Se selecciona especificamente el dia de la fecha seleccionada
        const dia_semana_seleccionada = fecha_seleccionada.getDay();

        // Se declara la fecha actual cruda
        const ahora = new Date();

        // Transforma la fecha a un estándar de fechas internacional (ISO)
        const fecha_actual = ahora.toISOString().split('T')[0];
        console.log("dia_semana: " + dia_semana_seleccionada);

        // Console logs para verificar que todo funca bien (borrar eventualmente)
        console.log("Fecha actual: " + fecha_actual);
        console.log(dia_falta_valor);

        // Si la fecha ingresada por el usuario es menor a la fecha actual, se detiene el programa
        if(dia_falta_valor < fecha_actual) {
            alerta_fallo("El dia seleccionado no es valido");
            return;
        }

        //Fetch especificando el tipo de respuesta que se le va a mandar al php (para q no hayan errores)
        const respuesta = await fetch("../../backend/functions/Profesores/inasistencia/cargar_horarios.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `fecha=${encodeURIComponent(dia_semana_seleccionada)}`
        });
            
        const data = await respuesta.json();

        // Guardar horarios en variable global (si hay datos los guarda, si no queda vacio)
        horariosDisponibles = data.horarios || [];

        // Si hay datos, se actualiza su visibilidad a visible
        if (data && horariosDisponibles.length > 0) {
            contenedor_horas_falta.style.visibility = "visible";
            
            // Si ya hay un valor en cantidad_horas_falta, generar los campos
            const cantidad_horas = document.getElementById("cantidad_horas_falta").value;
            if (cantidad_horas > 0) {
                generar_campos_horarios();
            }
        } else {
            alerta_fallo("No hay horarios disponibles para este día");
        }

    } catch (error) {
        console.error('Error completo:', error);
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