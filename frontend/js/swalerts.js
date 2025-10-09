import { borrar_todos_horarios } from './validaciones-registro.js';

export function alerta_fallo(mensaje){
    Swal.fire({
        title: "Ups...",
        text: mensaje,
        icon: "error"
    });

}

export function sw_exito(mensaje){
Swal.fire({
    title: "¡Éxito!",
    text: mensaje + ', reiniciando pagina...',
    icon: "success",
    timer: 1500, // Se cierra automáticamente en 1,5 segundos
    timerProgressBar: true, // Muestra barra de progreso
    showConfirmButton: false // Oculta el botón
}).then(() => {
    location.reload(); // Se ejecuta después de cerrar
});
}

export function sw_redirect(mensaje, direccion){
Swal.fire({
    title: "¡Éxito!",
    text: mensaje + ', reiniciando pagina...',
    icon: "success",
    timer: 1500, // Se cierra automáticamente en 1,5 segundos
    timerProgressBar: true, // Muestra barra de progreso
    showConfirmButton: false // Oculta el botón
}).then(() => {
    window.location.href = direccion;
});
}

export function sw_warning(mensaje){
    Swal.fire({
        title: "¡Alerta!",
        html: mensaje,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Borrar horarios y crear nuevos",
        cancelButtonText: "Continuar con los horarios actuales",
        customClass: {
            confirmButton: 'eliminar_horarios'
        }
    }).then((result) => {
        if (result.isConfirmed) {
                borrar_todos_horarios();
        }
    });
}
