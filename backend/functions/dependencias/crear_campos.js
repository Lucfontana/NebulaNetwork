//Se importan dos funciones de otro archivo:
import {alerta_fallo, sw_exito} from '../../../frontend/js/swalerts.js';

//guarda los valores ingresados en las variables variables
let campos_a_crear = document.getElementById("crear_campos");
let formulario_dependencias = document.querySelector(".dependencias-form");



//Cuando el usuario escribe un número se llama la función crear_campos. 
// Cuando se envía el formulario se llama registrar_dependencia.

//Cuando el usuario INGRESA un numero (input), se crean los campos
//Si existen los campos, se ejecuta
if(campos_a_crear) campos_a_crear.addEventListener("input", crear_campos)

if(formulario_dependencias) formulario_dependencias.addEventListener("submit", registrar_dependencia)



//Funcion asincronica, se usa para ejecutar el codigo mientras se hace las peticiones
//Y que el codigo no se detenga una vez llegue a las peticiones (fetch). Es lo mismo que habiamos
//usado en validaciones-regustro.js solo que ahi lo haciamos con .then, lo que a la larga podia ser confuso
async function crear_campos(){
    try {

        //Se trae el valor del input y el contenedor donde va a estar los select
        let cantidad_campos = parseInt(document.getElementById("crear_campos").value);
        let contenedor = document.getElementById("campos-dinamicos");

        //Validaciones por si se ingresan mas de 8 horas o menos de 0
        if (cantidad_campos <= 0 ){
            alerta_fallo("La cantidad de campos debe ser mayor a 0");
            return;
        }
        if (cantidad_campos > 8) {
            alerta_fallo("La cantidad de campos no puede ser mayor a 8");
            return;
        }

                        //El await es una PROMESA, le dice que haga fetch al archivo pero mientras hace eso, que siga
                        //ejecutando todo lo otro (para que la pagina no se quede estatica mientras se hace fetch).
        const respuesta = await fetch("../../backend/functions/dependencias/obtener_horarios.php");
        const data = await respuesta.json();
        //(Se hace una petición AJAX al archivo PHP que devuelve los horarios disponibles
        // El await hace que el código espere la respuesta antes de seguir, pero sin congelar la página.)


        //Si data.estado (viene del PHP) es distinto a 1 o no esta con valor, tira error
        if (!data.estado || data.estado !== '1') {
            alerta_fallo("Error al cargar horarios: " + data.mensaje);
            return;
        }

        let horarios = data.horarios; //extrae la lista de horarios y la guarda en la variable horarios para usarla en el código después
        
        //Si no hay horarios, o la respuesta esta vacia, hay alerta de que no hay hroarios
        if (!horarios || horarios.length === '0') {
            alerta_fallo("No hay horarios disponibles");
            return;
        }

        //Cada vez que cambia el valor del input, se vacia el contenido del contenedor
        contenedor.innerHTML = '';

        crear_selects_horarios(contenedor, horarios, cantidad_campos);

        //estructura de manejo de errores en js
    } catch (error) {
        console.error('Error completo:', error);
        alerta_fallo('Error al cargar los horarios'); //se ejecuta solo si ocurre un error dentro del bloque try.
    }
}


//Esta función crea los campos de selección (uno por cada número indicado).
export function crear_selects_horarios(contenedor, horarios, cantidad_campos) {
    console.log("creando campos");
    for (let i = 1; i <= cantidad_campos; i++) {
        let campoDiv = document.createElement('div'); //Crea un div con clase div-labels.
        campoDiv.className = 'div-labels';

        let label = document.createElement('label'); //Crea un label con el texto “Hora de la clase X”.
        label.textContent = `Hora de la clase ${i}:`;
        label.setAttribute('for', `hora_clase`);
        label.className = 'label';

        let select = document.createElement('select');
        select.className = 'input-register';
        select.id = `hora_profesor_da_clase${i}`;
        select.name = 'hora_profesor_da_clase[]';
        select.required = true;

        let opcionPredeterminada = document.createElement("option");
        opcionPredeterminada.value = '';
        opcionPredeterminada.textContent = 'Selecciona una opción';
        opcionPredeterminada.disabled = true;
        opcionPredeterminada.selected = true;
        select.appendChild(opcionPredeterminada);

        horarios.forEach(horario => {
            let opcion = document.createElement("option");
            opcion.value = horario.id_horario;
            opcion.textContent = `${horario.hora_inicio} - ${horario.hora_final}`;
            select.appendChild(opcion);
        });

        campoDiv.appendChild(label);
        campoDiv.appendChild(select);
        contenedor.appendChild(campoDiv);
    }
}


async function registrar_dependencia(e){
    e.preventDefault(); //Evita recargar la página

    let profesor_asignado = document.getElementById("profesor_asignado").value; //obtiene los valores de los campos
    let asignatura_dictada = document.getElementById("asignatura_dada").value;
    let salon_ocupado = document.getElementById("salon_a_ocupar").value;
    let curso_dictado = document.getElementById("curso_dictado").value;
    let dia_dictado = document.getElementById("dia_dictado").value;
    
    // Obtener todos los selects de horarios
    let selects_horarios = document.getElementsByName("hora_profesor_da_clase[]");
    let horas_dictadas = [];
    
    for(let select of selects_horarios) {
        if(select.value) {
            horas_dictadas.push(select.value);
        }
    }
    
    if(horas_dictadas.length === 0) {
        alerta_fallo("Debes seleccionar al menos un horario");
        return;
    }


//Se construye un formulario virtual (sin HTML) para enviar los datos mediante fetch.
    const form_dependencia = new FormData();
    form_dependencia.append('profesor_asignado', profesor_asignado);
    form_dependencia.append('asignatura_dada', asignatura_dictada);
    
    // Agregar cada horario individualmente
    horas_dictadas.forEach(hora => {
        form_dependencia.append('hora_profesor_da_clase[]', hora);
    });
    
    form_dependencia.append('salon_ocupado', salon_ocupado);
    form_dependencia.append('curso_dictado', curso_dictado);
    form_dependencia.append('dia_dictado', dia_dictado)
    form_dependencia.append('registrarDependencia', true);


    let respuesta = await fetch("../../backend/functions/dependencias/dependencias_api.php", {
                method: 'POST',
                body: form_dependencia
    });

    let data = await respuesta.json();

    if (data.estado === 1){
        sw_exito(`${data.mensaje}`);
    } else {
        alerta_fallo(`${data.mensaje}`);
    }

}