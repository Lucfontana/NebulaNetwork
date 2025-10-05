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