//código  para enviar un formulario al servidor sin recargar la página


//Importa dos funciones (alerta_fallo y sw_exito) desde el las sweetalerts
import { alerta_fallo, sw_exito } from '/frontend/js/swalerts.js';

//Busca en el DOM (la página) el primer elemento que tenga la clase prestar-form y lo asigna a la variable prestar_recursos.
let prestar_recursos = document.querySelector(".prestar-form");
                                                //Llama a la funcion validar_asignaturas
prestar_recursos.addEventListener("submit", prestar_recurso);
//Añade un listener para el evento submit del formulario (cuando el usuario envía el formulario).
//Cuando se dispare submit, se ejecutará la función prestar_recurso.

//Declara la función prestar_recurso, que recibe el objeto de evento (evento) producido por el submit.
function prestar_recurso(evento) {
    
    // Evita que se recargue la página
    evento.preventDefault();

    // Obtener el nombre de la asignatura (aca se llamarian a todos los datos)
    let profesor_prestado = document.getElementById("profesor_asignado").value;
    let recurso_prestado = document.getElementById("recurso_prestado").value;
//.value devuelve el texto (o el valor seleccionado) en esos inputs.

    //se crea un objeto formdata para tomar los valores del formulario (aca se pondrian todos los datos con .append)
    const formData = new FormData();
                    //name del campo      valor a pasarle
    formData.append('profesor_asignado', profesor_prestado);
    formData.append('recurso_prestado', recurso_prestado);
    formData.append('prestarRecurso', true);
//append añade un nuevo campo al objeto FormData.
  
    // se le pasa al fetch el endpoint que genera la consulta de busqueda, se pone la direccion del php
    fetch('../backend/functions/Recursos/prestar-recursos/prestar_api.php', {
        method: 'POST', //indica que es una petición POST.
        body: formData //envía el FormData.
    })

    //se toma la respuesta y se devuelve en formato json
    .then(response => response.json()) 
    //la variable data se usa para recorrer el array asociativo del endpoint...
    .then(data => { //data es el objeto resultante del JSON

        //si el enpoint devuelve 1...
        if (data.estado === 1) {
            sw_exito(`${data.mensaje}`);
        } else {
            alerta_fallo(`${data.mensaje}`);
        }
    })
    .catch(error => { //Captura cualquier error ocurrido durante la petición fetch o durante el parseo JSON 
    // y lo muestra en la consola con console.error.
        console.error('Error:', error);
    });
}
