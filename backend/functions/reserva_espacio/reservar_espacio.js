//Se importan funciones a usar mas adelante
import { alerta_fallo, sw_exito } from '../../../../frontend/js/swalerts.js';
import { crear_selects_horarios } from '../dependencias/crear_campos.js';

let dia_reservar = document.getElementById("dia_reserva");
let espacio_reserva = document.getElementById("espacio_reservar");
let reserva_form = document.querySelector(".reserva-form");
let cantidad_horas = document.getElementById("cantidad_horas_reserva");
// Variable global para almacenar los horarios obtenidos
let horariosDisponibles = [];

//Horas a reservar esta oculto desde un inicio
let horas_reservar = document.getElementById("horas_reserva");
horas_reservar.style.visibility = "hidden";

//Event listeners
dia_reservar.addEventListener("input", cargar_horarios);
espacio_reserva.addEventListener("input", cargar_horarios);
cantidad_horas.addEventListener("input", generar_campos_horarios);
reserva_form.addEventListener("submit", registrar_reserva);

async function cargar_horarios() {
    try {
        let valor_fecha_reservado = document.getElementById("dia_reserva").value;
        let valor_espacio_reserva = document.getElementById("espacio_reservar").value;

        //Se calcula el dia de la semana y se lo fuerza a que sea de la hora local.
        //Se divide la fecha en cada - que hay (si la fecha es 2025-10-10, queda un array de [2025, 10, 10])
        //el .map pasa cada valor a numero
        const [año, mes, dia] = valor_fecha_reservado.split('-').map(Number);

        let fecha_seleccionada = new Date(año, mes - 1, dia);

        let dia_semana_seleccionada = fecha_seleccionada.getDay();

                            //Se ordena como anio mes dia, por eso se lo pasa a fecha
                            //canadiense (tiene ese formato y la BD se maneja asi)
        fecha_seleccionada = fecha_seleccionada.toLocaleDateString('en-CA');

        if (dia_semana_seleccionada === 0 || dia_semana_seleccionada === 6) {
            alerta_fallo("No se pueden realizar reservas en los fines de semana");
            horas_reservar.style.visibility = "hidden";
            return;
        }


        //El PHP maneja los dias como si el 0 fuera lunes, 1 = martes y asi progresivamente, por lo que
        //adaptamos el valor
        dia_semana_seleccionada = dia_semana_seleccionada - 1;

        //Si ambas variables tienen un valor asignado, se hace fetch para 
        //mostrar las horas libres de ese espacio fisico en ese dia en especifico
        if (valor_fecha_reservado && valor_espacio_reserva) {
            console.log("Las variables TIENEN un valor asignado.");
            horas_reservar.style.visibility = "visible";

        const respuesta = await fetch("../../backend/functions/reserva_espacio/horas_libres.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
                                            //Los valores se concatenan con & (las string se concatenan asi)
            body: `fecha=${encodeURIComponent(dia_semana_seleccionada)}&espacio=${encodeURIComponent(valor_espacio_reserva)}&fecha_seleccionada=${encodeURIComponent(fecha_seleccionada)}`
        });

        const data = await respuesta.json();

        horariosDisponibles = data.horarios || [];

        //Si el estado del fetch es 1 (1 significa que todo esta bien) y hay mas de un horario, prosigue
        if (data.estado === '1' && horariosDisponibles.length > 0) {

        //Se trae el valor de la cantidad de horas, si es mayor a uno se generan los campos
        const cantidad_horas_reserva = document.getElementById("cantidad_horas_falta").value;
            if (cantidad_horas_reserva > 0 || cantidad_horas_reserva <= 15)  {
                generar_campos_horarios();
            }
        } else {
            alerta_fallo(data.mensaje || "No hay horarios disponibles para este día");
        }

        } else {
            horas_reservar.style.visibility = "hidden";
        }

    } catch (error) {
        console.error('Error:', error);
        alerta_fallo('Error al hacer absolutamente nada');
    }
    
}

// Funcion que genera los campos cuando cambia cantidad_horas_falta
function generar_campos_horarios() {
    const cantidad_horas_reserva = document.getElementById("cantidad_horas_reserva").value;
    const contenedor = document.getElementById("campos-reservas");
    
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
    crear_selects_horarios(contenedor, horariosDisponibles, cantidad_horas_reserva);
}

async function registrar_reserva(e){
    e.preventDefault();

    let dia_reservar = document.getElementById("dia_reserva").value;
    const espacio_reservar = document.getElementById("espacio_reservar").value;

    // Solo calculamos el día de la semana para enviarlo al PHP (explicacion de eso arriba)
    const [año, mes, dia] = dia_reservar.split('-').map(Number);
    let fecha_reservar = new Date(año, mes - 1, dia);
    let dia_semana_seleccionada = fecha_reservar.getDay();

    fecha_reservar = fecha_reservar.toLocaleDateString('en-CA');
    
    //Si se selecciono domingo/sabado que salga error
    if (dia_semana_seleccionada === 0 || dia_semana_seleccionada === 6) {
        alerta_fallo("No se pueden registrar inasistencias los fines de semana");
        return;
    }

    dia_semana_seleccionada = dia_semana_seleccionada - 1;


    //Obtiene TODOS los elementos <select> que tengan name="hora_profesor_da_clase[]"
    let horas_reservar = document.getElementsByName("hora_profesor_da_clase[]");
    
    //Array vacio donde guardamos solo los horarios que el usuario selecciono
    let horas_reservadas = [];
    
    //Recorremos cada select
    for(let select of horas_reservar) {
        // Si el select tiene un valor (el usuario selecciono algo)
        if(select.value) {
            //Agregamos ese valor al array
            //Ejemplo: si value="15", se agrega 15 al array
            horas_reservadas.push(select.value);
        }
    }
    
    //Si el usuario no eligio nada, le sale alerta (por las dudas, ya que los select tienen 'required')
    if(horas_reservadas.length === 0) {
        alerta_fallo("Debes seleccionar al menos un horario");
        return;
    }

    //Se crea el formulario y se pasan sus valores para cada campo que existe
    const form_reserva = new FormData();

    horas_reservadas.forEach(hora => {
        form_reserva.append('hora_profesor_da_clase[]', hora);
    });

    form_reserva.append("dia_semana_seleccionada", dia_semana_seleccionada);
    form_reserva.append("espacio_reservar", espacio_reservar);
    form_reserva.append("fecha_reservar", fecha_reservar);
    form_reserva.append("registrarReservaEspacio", true);

    //Se hace fetch al php que maneja el formulario (para que se hagan las inserciones)
    let respuesta = await fetch("../../backend/functions/reserva_espacio/reserva_esp_api.php", 
        {
            method: "POST",
            body: form_reserva
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

