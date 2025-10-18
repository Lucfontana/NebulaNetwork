import { alerta_fallo, sw_exito } from '/frontend/js/swalerts.js';

let prestar_recursos = document.querySelector(".prestar-form");
                                                //Llama a la funcion validar_asignaturas
prestar_recursos.addEventListener("submit", prestar_recurso);

function prestar_recurso(evento) {
    
    // Evita que se recargue la página
    evento.preventDefault();

    // Obtener el nombre de la asignatura (aca se llamarian a todos los datos)
    let profesor_prestado = document.getElementById("profesor_asignado").value;
    let recurso_prestado = document.getElementById("recurso_prestado").value;
    //se crea un objeto para tomar los valores del formulario (aca se pondrian todos los datos con .append)
    const formData = new FormData();
                    //id del campo      valor a pasarle
    formData.append('profesor_asignado', profesor_prestado);
    formData.append('recurso_prestado', recurso_prestado);
    formData.append('prestarRecurso', true);

    // se le pasa al fetch el endpoint que genera la consulta de busqueda, se pone la direccion del php
    fetch('../backend/functions/Recursos/prestar-recursos/prestar_api.php', {
        method: 'POST',
        body: formData
    })

    //se toma la respuesta y se devuelve en formato json
    .then(response => response.json())
    //la variable data se usa para recorrer el array asociativo del endpoint...
    .then(data => {

        //si el enpoint devuelve 1...
        if (data.estado === 1) {
            sw_exito(`${data.mensaje}`);
        } else {
            alerta_fallo(`${data.mensaje}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
