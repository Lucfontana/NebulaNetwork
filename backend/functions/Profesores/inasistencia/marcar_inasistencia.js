//Se importan funciones a usar mas adelante
import { alerta_fallo, sw_exito } from '../../../../frontend/js/swalerts.js';
import { crear_selects_horarios } from '../../dependencias/crear_campos.js';

//alerta_fallo: muestra una alerta de error (por ejemplo, con SweetAlert). 
//sw_exito: muestra una alerta de éxito.
//crear_selects_horarios: función que genera los campos <select> para que el usuario elija los horarios de inasistencia.

//Se declaran variables para ir creando los campos
const dia_falta = document.getElementById("dia_falta");
const cantidad_horas_falta = document.getElementById("cantidad_horas_falta");
const formulario_falta = document.querySelector(".inasistencia-form");

// Variable global para almacenar los horarios obtenidos
let horariosDisponibles = [];
//Variable que inicialmente es un arreglo vacío; se usará para guardar los horarios que vienen desde 
// el servidor (fetch). let porque su contenido cambiará.


// Event listeners

//Cuando el usuario cambia el valor del campo dia_falta (elige otra fecha), 
// se llama a la función cargar_horarios_dia.
dia_falta.addEventListener("change", cargar_horarios_dia); 

//Cada vez que cambia el valor del input de cantidad de horas, 
// se llama a generar_campos_horarios para actualizar los selects.
cantidad_horas_falta.addEventListener("input", generar_campos_horarios);

//Al enviar el formulario, se ejecuta registrar_falta (controla envío, valida y hace fetch para registrar la inasistencia).
formulario_falta.addEventListener("submit", registrar_falta);//se ejecuta cuando envía el formulario


// Funcion que hace el fetch cuando cambia el día
async function cargar_horarios_dia() {
    try {
        const dia_falta_valor = document.getElementById("dia_falta").value; 
        //Obtiene el valor actual del input dia_falta como string

        const contenedor_horas_falta = document.getElementById("horas_falta");
        //se guarda una referencia a un contenedor DOM con id horas_falta (para mostrar/ocultar la sección de horarios).
        
        //Oculta visualmente ese contenedor mientras se carga/valida la información.
        contenedor_horas_falta.style.visibility = "hidden";

        //Se calcula el dia de la semana y se lo fuerza a que sea de la hora local.
        //Se divide la fecha en cada - que hay (si la fecha es 2025-10-10, queda un array de [2025, 10, 10])
        //el .map pasa cada valor a numero
        const [año, mes, dia] = dia_falta_valor.split('-').map(Number);
        //divide la cadena YYYY-MM-DD en componentes y los convierte a números: año, mes, dia.

        //Crea un objeto Date con la fecha seleccionada
        //Se le resta 1 al mes porque en js los meses van del 0 al 11, 
        //y arriba estabamos consiguiendo los meses del 1-12
        const fecha_seleccionada = new Date(año, mes - 1, dia);
        let dia_semana_seleccionada = fecha_seleccionada.getDay();
        //Obtiene el día de la semana como número 0–6 donde 0 = domingo, 1 = lunes, etc.
        
        //El PHP maneja los dias como si el 0 fuera lunes, 1 = martes y asi progresivamente, por lo que
        //adaptamos el valor
        dia_semana_seleccionada = dia_semana_seleccionada - 1;



        //Esto enviará al servidor (PHP) el día de la semana que eligió el usuario y esperar que el 
        // servidor devuelva los horarios disponibles para ese día.

        //Fetch especificando el tipo de respuesta que se le va a mandar al php (para q no hayan errores)
        const respuesta = await fetch("../../backend/functions/Profesores/inasistencia/cargar_horarios.php", {
            method: "POST",
            headers: {//Indica que el body está codificado como application/x-www-form-urlencoded (como un formulario tradicional).
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `fecha=${encodeURIComponent(dia_semana_seleccionada)}`
            //Envía el campofecha con el valor (codificado) del día de la semana calculado.
        });
            
        const data = await respuesta.json();
        //El método .json() lee el cuerpo de la respuesta (que viene como texto) y lo convierte automáticamente a un objeto JavaScript.

        //Asigna a la variable global horariosDisponibles el arreglo data.horarios si existe; si no, deja un arreglo vacío.
        horariosDisponibles = data.horarios || [];

        //Si el estado del fetch es 1 (1 significa que todo esta bien) y hay mas de un horario, prosigue
        if (data.estado === '1' && horariosDisponibles.length > 0) {
            contenedor_horas_falta.style.visibility = "visible"; //Si está todo bien, hace visible el contenedor de horarios.
            
            //Se trae el valor de la cantidad de horas, si es mayor a uno se generan los campos
            const cantidad_horas = document.getElementById("cantidad_horas_falta").value;
            if (cantidad_horas > 0) {
                generar_campos_horarios();
            }
        } else { //Si no hay horarios, muestra un mensaje de error.
            alerta_fallo(data.mensaje || "No hay horarios disponibles para este día");
        }

     //Bloque que captura cualquier excepción lanzada dentro del try (por ejemplo problemas de red o JSON inválido).
    } catch (error) { 
        console.error('Error:', error);
        alerta_fallo('Error al cargar los horarios');
    }
}

// Funcion que genera los campos cuando cambia cantidad_horas_falta
function generar_campos_horarios() {
    const cantidad_horas = document.getElementById("cantidad_horas_falta").value; //Lee el valor actual de cantidad de horas.
    const contenedor = document.getElementById("campos-dinamicos");//guarda referencia al contenedor donde se colocarán los selects dinámicos.
    
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

//Función asíncrona que se ejecuta al enviar el formulario. Recibe el evento e.
async function registrar_falta(e){
    e.preventDefault(); //Evita el envío tradicional del formulario (recarga de página).

    //Toma el valor de la fecha seleccionada por el usuario.
    let dia_faltar = document.getElementById("dia_falta").value;
    
    // Solo calculamos el día de la semana para enviarlo al PHP (explicacion de eso arriba)
    const [año, mes, dia] = dia_faltar.split('-').map(Number);
    let dia_faltar_profe = new Date(año, mes - 1, dia);
    let dia_semana_seleccionada = dia_faltar_profe.getDay();
//Se repite el proceso de parsear YYYY-MM-DD, crear Date y obtener getDay() para saber qué día de la semana es.

    
    //Si se selecciono domingo/sabado que salga error
    if (dia_semana_seleccionada === 0 || dia_semana_seleccionada === 6) {
        alerta_fallo("No se pueden registrar inasistencias los fines de semana");
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
    if(dia_faltar < fecha_actual) {
        alerta_fallo("El dia seleccionado no es valido");
        return;
    }

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
    form_falta.append("dia_falta", dia_faltar); //Agrega el campo dia_falta.

    horas_faltadas.forEach(hora => { //Agrega uno por uno los valores de horario con el mismo name hora_profesor_da_clase[]
        form_falta.append('hora_profesor_da_clase[]', hora);
    });

    form_falta.append("dia_semana_seleccionada", dia_semana_seleccionada); //Agrega el día de la semana ajustado.
    form_falta.append("registrarFalta", true); //Agrega un flag registrarFalta para que el backend sepa qué acción ejecutar.

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