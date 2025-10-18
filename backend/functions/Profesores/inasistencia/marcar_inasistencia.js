//Se importan funciones a usar mas adelante
import { alerta_fallo, sw_exito } from '../../../../frontend/js/swalerts.js';
import { crear_selects_horarios } from '../../dependencias/crear_campos.js';

//Se declaran variables para ir greando los campos
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

        //Se calcula el dia de la semana y se lo fuerza a que sea de la hora local.
        //Se divide la fecha en cada - que hay (si la fecha es 2025-10-10, queda un array de [2025, 10, 10])
        //el .map pasa cada valor a numero
        const [año, mes, dia] = dia_falta_valor.split('-').map(Number);

        //Se le resta 1 al mes porque en js los meses van del 0 al 11, 
        //y arriba estabamos consiguiendo los meses del 1-12
        const fecha_seleccionada = new Date(año, mes - 1, dia);
        let dia_semana_seleccionada = fecha_seleccionada.getDay();
        
        //Si el dia de la semana es Domingo (representado con 0) o sabado (6) 
        //indica quue no se puede faltar en esos dias
        if (dia_semana_seleccionada === 0) {
            alerta_fallo("No se pueden registrar inasistencias los domingos");
            return;
        }
        if (dia_semana_seleccionada === 6) {
            alerta_fallo("No se pueden registrar inasistencias los sábados");
            return;
        }
        
        //El PHP maneja los dias como si el 0 fuera lunes, 1 = martes y asi progresivamente, por lo que
        //adaptamos el valor
        dia_semana_seleccionada = dia_semana_seleccionada - 1;

        // Se declara la fecha actual cruda
        const ahora = new Date();

        // Transforma la fecha a un estándar de fechas internacional (ISO)
        const fecha_actual = ahora.toISOString().split('T')[0];

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

        horariosDisponibles = data.horarios || [];

        //Si el estado del fetch es 1 (1 significa que todo esta bien) y hay mas de un horario, prosigue
        if (data.estado === '1' && horariosDisponibles.length > 0) {
            contenedor_horas_falta.style.visibility = "visible";
            
            //Se trae el valor de la cantidad de horas, si es mayor a uno se generan los campos
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
    if (cantidad_horas <= 0 || cantidad_horas >= 20) {
        return;
    }
    
    // Crear los selects con los horarios, proviene de dependencias/crear_campos.js
    crear_selects_horarios(contenedor, horariosDisponibles, cantidad_horas);
}

async function registrar_falta(e){
    e.preventDefault();

    let dia_faltar = document.getElementById("dia_falta").value;

    // Solo calculamos el día de la semana para enviarlo al PHP (explicacion de eso arriba)
    const [año, mes, dia] = dia_faltar.split('-').map(Number);
    let dia_faltar_profe = new Date(año, mes - 1, dia);
    let dia_semana_seleccionada = dia_faltar_profe.getDay();
    
    //Si se selecciono domingo/sabado que salga error
    if (dia_semana_seleccionada === 0 || dia_semana_seleccionada === 6) {
        alerta_fallo("No se pueden registrar inasistencias los fines de semana");
        return;
    }

    dia_semana_seleccionada = dia_semana_seleccionada - 1;


    //Obtiene TODOS los elementos <select> que tengan name="hora_profesor_da_clase[]"
    let horas_faltar = document.getElementsByName("hora_profesor_da_clase[]");
    
    //Array vacio donde guardamos solo los horarios que el usuario selecciono
    let horas_faltadas = [];
    
    //Recorremos cada select
    for(let select of horas_faltar) {
        // Si el select tiene un valor (el usuario selecciono algo)
        if(select.value) {
            //Agregamos ese valor al array
            //Ejemplo: si value="15", se agrega 15 al array
            horas_faltadas.push(select.value);
        }
    }
    
    //Si el usuario no eligio nada, le sale alerta (por las dudas, ya que los select tienen 'required')
    if(horas_faltadas.length === 0) {
        alerta_fallo("Debes seleccionar al menos un horario");
        return;
    }

    //Se crea el formulario y se pasan sus valores para cada campo que existe
    const form_falta = new FormData();
    form_falta.append("dia_falta", dia_faltar);

    horas_faltadas.forEach(hora => {
        form_falta.append('hora_profesor_da_clase[]', hora);
    });

    form_falta.append("dia_semana_seleccionada", dia_semana_seleccionada);
    form_falta.append("registrarFalta", true);

    //Se hace fetch al php que maneja el formulario (para que se hagan las inserciones)
    let respuesta = await fetch("../../backend/functions/Profesores/inasistencia/reg_inasistencia_api.php", 
        {
            method: "POST",
            body: form_falta
        }
    );

    let data = await respuesta.json();

    //Si el estado es 1 quiere decir que todo salio bien. Si no, quiere decir que hay error
    if (data.estado === 1){
        sw_exito(`${data.mensaje}`);
    } else {
        alerta_fallo(`${data.mensaje}`);
    }
}