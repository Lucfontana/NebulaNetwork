
//Este codigo gestiona la devolución de recursos desde los botones.

import { alerta_fallo, sw_exito } from '../../../../frontend/js/swalerts.js';

// Espera a que el documento esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    
    // Selecciona todos los botones de devolver y los guarda en una lista (NodeList) llamada botonesDevolver.
    const botonesDevolver = document.querySelectorAll('.btn-devolver');
    
    // Agrega evento click a cada botón, que ejecutará la función indicada cuando el usuario haga clic.
    botonesDevolver.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault(); // Evita que el enlace recargue la página
            
            // Obtiene los valores de los atributos del botón clicado:
            const idSolicita = this.getAttribute('data-id');
            const idRecurso = this.getAttribute('data-recurso');
            const nombreRecurso = this.getAttribute('data-nombre-recurso');
            
            //Usa SweetAlert para mostrar una ventana de confirmación.
            // Pide confirmación al usuario
            Swal.fire({
                title: "¿Estás seguro?",
                text: `¿Confirmar devolución del recurso "${nombreRecurso}"?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Confirmar", 
                cancelButtonText: "No"
                }).then((result) => {
                    if (result.isConfirmed) { //Cuando el usuario confirma, llama a la función hacer_devolucion pasando los IDs.
                        hacer_devolucion(idSolicita, idRecurso);
                    }
                });
        });
    });
});

//Función que será la encargada de comunicar al servidor que se devuelva el recurso.
function hacer_devolucion(idSolicita, idRecurso){

            // Crea objeto FormData para enviar los datos mediante POST.
            const formData = new FormData();
            formData.append('id_solicita', idSolicita);
            formData.append('id_recurso', idRecurso);
            formData.append('devolverRecurso', true);
            
            // Envía la petición AJAX al servidor
            //Envía los datos al archivo PHP del backend usando fetch.
            fetch('../../backend/functions/Recursos/prestar-recursos/procesar_devolucion.php', {
                method: 'POST',
                body: formData
        //Se usa el método POST y el cuerpo de la petición es el FormData con la información de la devolución.
            })
            //Espera la respuesta del servidor y la convierte en un objeto JSON.
            .then(response => response.json()) // Convierte la respuesta a JSON
                .then(data => {
                if (data.success) {
                    // Si fue exitoso, muestra mensaje
                    sw_exito(data.message);
                } else {
                    // Si hubo error, muestra el mensaje de error
                    alerta_fallo(data.message);
                }
            })

}

