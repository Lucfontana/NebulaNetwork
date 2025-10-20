import { alerta_fallo, sw_exito } from '../../../../frontend/js/swalerts.js';
// Espera a que el documento esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    
    // Selecciona todos los botones de devolver
    const botonesDevolver = document.querySelectorAll('.btn-devolver');
    
    // Agrega evento click a cada botón
    botonesDevolver.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault(); // Evita que el enlace recargue la página
            
            // Obtiene los datos del botón
            const idSolicita = this.getAttribute('data-id');
            const idRecurso = this.getAttribute('data-recurso');
            const nombreRecurso = this.getAttribute('data-nombre-recurso');
            
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
                    if (result.isConfirmed) {
                        hacer_devolucion(idSolicita, idRecurso);
                    }
                });
        });
    });
});

function hacer_devolucion(idSolicita, idRecurso){
            // Crea objeto FormData para enviar los datos
            const formData = new FormData();
            formData.append('id_solicita', idSolicita);
            formData.append('id_recurso', idRecurso);
            formData.append('devolverRecurso', true);
            
            // Envía la petición AJAX al servidor
            fetch('../../backend/functions/Recursos/prestar-recursos/procesar_devolucion.php', {
                method: 'POST',
                body: formData
            })
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

