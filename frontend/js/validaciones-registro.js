import { verificarCI, verificarExistenciaCI, mostrarDigVerificador, CIaArreglo } from './validarCI.js';
//-----FORMULARIOS-----//

//Por cada formulario, se lo debe llamar (cuando se envia) y verificar que todos sus
//campos esten limpios y correctos

                                    //querySelector busca y agarra el primer elemento que
                                    //coincida con selector especifico

                                    //"¿Y porque no buscar por la id? no quedamas facil?"
                                    //no pq todos los formularios tienen la misma id y eso afectaria el
                                    //register-modal que hizo lautaro (CREO, si quieren prueben)
let formulario_asignaturas = document.querySelector(".asignatura-form");
                                                //Llama a la funcion validar_asignaturas
formulario_asignaturas.addEventListener("submit", validar_asignaturas);

function validar_asignaturas(evento) {
    
    // Evita que se recargue la página
    evento.preventDefault();

    // Obtener el nombre de la asignatura (aca se llamarian a todos los datos)
    let nombre_asignatura = document.getElementById("nombreAsignatura").value;

    //se crea un objeto para tomar los valores del formulario (aca se pondrian todos los datos con .append)
    const formData = new FormData();
                    //id del campo      valor a pasarle
    formData.append('nombreAsignatura', nombre_asignatura);
    formData.append('registrarAsignatura', true);

    //Se llaman a todas las funciones de verificar
    if (!verificarString(nombre_asignatura, "nombre")){ // entre comillas ponemos "nombre" porque es lo que validamos, 
                                                        //Si verificaramos "apellido" ahi adentro iria apellido
        evento.preventDefault(); //Se previene el envio del formulario 
        return;
    }

    // se le pasa al fetch el endpoint que genera la consulta de busqueda, se pone la direccion del php
    fetch('../../backend/functions/asignaturas/asignaturas_api.php', {
        method: 'POST',
        body: formData
    })

    //se toma la respuesta y se devuelve en formato json
    .then(response => response.json())
    //la variable data se usa para recorrer el array asociativo del endpoint...
    .then(data => {

        //si el enpoint devuelve 1...
        if (data.estado === 1) {
            alerta_success(`${data.mensaje}`, "asignaturas.php", "asignaturas");
        } else {
            alerta_fallo(`${data.mensaje}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

//------------------VALIDACIONES-------------------//
//-------------------------------------------------//
//Nota: Las validaciones de CI ya estan creadas en el archivo de 'login.js', quedaria importar el archivo y las funciones

let ci_profesor = document.getElementById("ciProfesor");
ci_profesor.addEventListener("input", function() {
    ci_profesor.value = ci_profesor.value.replace(/[^0-9]/g, '').slice(0, 8);
});
let contrasena_profesor = document.getElementById("contrasenaProfesor");
contrasena_profesor.addEventListener("input", function() {
    contrasena_profesor.value = contrasena_profesor.value.replace(/[^0-9]/g, '').slice(0, 8);
});
let ci_superusuario = document.getElementById("ciSuperusuario");
ci_superusuario.addEventListener("input", function() {
    ci_superusuario.value = ci_superusuario.value.replace(/[^0-9]/g, '').slice(0, 8);
});
let contrasena_superusuario = document.getElementById("contrasenaSuperusuario");
contrasena_superusuario.addEventListener("input", function() {
    contrasena_superusuario.value = contrasena_superusuario.value.replace(/[^0-9]/g, '').slice(0, 8);
});


//Verificar en el documento de Drive de "Tareas pendientes PROYECTO" las distintas funciones que hay para hacer

let formulario_profesores = document.querySelector(".profesores-form");

formulario_profesores.addEventListener("submit", registrar_profesores)

function registrar_profesores(e){
    e.preventDefault();

    let ci_profesor = document.getElementById("ciProfesor").value;
    let contrasena_profesor = document.getElementById("contrasenaProfesor").value;
    let nombre_profesor = document.getElementById("nombreProfesor").value;
    let apellido_profesor = document.getElementById("apellidoProfesor").value;
    let email_profesor = document.getElementById("emailProfesor").value;
    let fecha_nacimiento_profesor = document.getElementById("fechaNacimientoProfesor").value;
    let direccion_profesor = document.getElementById("direccionProfesor").value;

    const form_profesor = new FormData();

    form_profesor.append('CI', ci_profesor);
    form_profesor.append('contrasena', contrasena_profesor);
    form_profesor.append('name', nombre_profesor);
    form_profesor.append('apellido', apellido_profesor);
    form_profesor.append('email', email_profesor);
    form_profesor.append('nac', fecha_nacimiento_profesor);
    form_profesor.append('direc', direccion_profesor);
    form_profesor.append('registroProfesor', true);

    if (!verificarCI(ci_profesor) || !verificarExistenciaCI(ci_profesor, [2,9,8,7,6,3,4])) {
        alerta_fallo("La cédula ingresada no es válida.");
        e.preventDefault();
        return;
    }
    if (contrasena_profesor !== ci_profesor) {
        alerta_fallo("La contraseña debe ser igual a la cédula.");
        e.preventDefault();
        return;
    }
    if (!verificarString(nombre_profesor, "nombre")) {
        alerta_fallo("El nombre ingresado no es válido.");
        e.preventDefault();
        return;
    }
    if (!verificarString(apellido_profesor, "apellido")) {
        alerta_fallo("El apellido ingresado no es válido.");
        e.preventDefault();
        return;
    }
    if (!verificarEmail(email_profesor)) {
        alerta_fallo("El email ingresado no es válido.");
        e.preventDefault();
        return;
    }
    if (!verificarFechanacimiento(fecha_nacimiento_profesor)) {
        alerta_fallo("La fecha de nacimiento ingresada no es válida.");
        e.preventDefault();
        return;
    }
    if (!verificarDireccion(direccion_profesor)) {
        alerta_fallo("La dirección ingresada no es válida.");
        e.preventDefault();
        return;
    }

    // se le pasa al fetch el endpoint que genera la consulta de busqueda, se pone la direccion del php
    fetch('../../backend/functions/Profesores/profesores_func.php', {
        method: 'POST',
        body: form_profesor
    })

    //se toma la respuesta y se devuelve en formato json
    .then(response => response.json())
    //la variable data se usa para recorrer el array asociativo del endpoint...
    .then(data => {

        //si el enpoint devuelve 1...
        if (data.estado === 1) {
            alerta_success(`${data.mensaje}`, "Profesores.php", "profesor");
        } else {
            alerta_fallo(`${data.mensaje}`);
        }
    })
};



let formulario_superusuarios = document.querySelector(".superusuarios-form");
formulario_superusuarios.addEventListener("submit", registrar_superusuario);

function registrar_superusuario(e) {
    e.preventDefault();

    let ci_superusuario = document.getElementById("ciSuperusuario").value;
    let contrasena_superusuario = document.getElementById("contrasenaSuperusuario").value;
    let nombre_superusuario = document.getElementById("nombreSuperusuario").value;
    let apellido_superusuario = document.getElementById("apellidoSuperusuario").value;
    let email_superusuario = document.getElementById("emailSuperusuario").value;
    let acceso_superusuario = document.getElementById("acceso").value;


    if (!verificarCI(ci_superusuario) || !verificarExistenciaCI(ci_superusuario, [2,9,8,7,6,3,4])) {
        alerta_fallo("La cédula ingresada no es válida."); // ✅ Agregado mensaje
        return;
    }
    if (contrasena_superusuario !== ci_superusuario) {
        alerta_fallo("La contraseña debe ser igual a la cédula.");
        return;
    }
    if (!verificarString(nombre_superusuario, "nombre")) {
        alerta_fallo("El nombre ingresado no es válido."); // ✅ Agregado mensaje
        return;
    }
    if (!verificarString(apellido_superusuario, "apellido")) {
        alerta_fallo("El apellido ingresado no es válido."); // ✅ Agregado mensaje
        return;
    }
    if (!verificarEmail(email_superusuario)) {
        alerta_fallo("El email ingresado no es válido.");
        return;
    }

    // ✅ Crear FormData DESPUÉS de validar
    const form_superusuario = new FormData();
    form_superusuario.append('CI', ci_superusuario);
    form_superusuario.append('password', contrasena_superusuario);
    form_superusuario.append('name', nombre_superusuario);
    form_superusuario.append('apellido', apellido_superusuario);
    form_superusuario.append('acceso', acceso_superusuario);
    form_superusuario.append('registrarSuperuser', true);

    fetch('../../backend/functions/SuperUsuarios/superusuarios_func.php', {
        method: 'POST',
        body: form_superusuario
    })
    .then(response => response.json())
    .then(data => {
        if (data.estado === 1) {
            alerta_success(`${data.mensaje}`, "SuperUsuarios.php", "superusuario");
        } else {
            alerta_fallo(`${data.mensaje}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alerta_fallo("Error al conectar con el servidor.");
    });
}

let formulario_recursos = document.querySelector(".recursos-form");
formulario_recursos.addEventListener("submit", function(e) {
    let nombre_recurso = document.getElementById("nombreRecurso").value;
    let estado_recurso = document.getElementById("estadoRecurso").value;

    if (!verificarNombreEspecial(nombre_recurso)) {
        e.preventDefault();
    }

});

let formulario_espacios = document.querySelector(".espacios-form");
formulario_espacios.addEventListener("submit", function(e) {
    let nombre_espacio = document.getElementById("nombreEspacio").value;
    let capacidad_espacio = document.getElementById("capacidadEspacio").value;
    let tipo_espacio = document.getElementById("tipoEspacio").value;

    if (!verificarNombreEspecial(nombre_espacio)) {
        e.preventDefault();
    }
    if (!verificarCapacidad(capacidad_espacio)) {
        e.preventDefault();
    }
});



let formulario_cursos = document.querySelector(".cursos-form");
formulario_cursos.addEventListener("submit", function(e) {
    let nombre_curso = document.getElementById("nombreCurso").value;
    let capacidad_curso = document.getElementById("capacidadCurso").value;

    if (!verificarNombreEspecial(nombre_curso)) {
        e.preventDefault();
    }
    if (!verificarCapacidad(capacidad_curso)) {
        e.preventDefault();
    }
});

let formulario_horarios = document.querySelector(".horarios-form");
formulario_horarios.addEventListener("submit", function(e) {
    let hora_inicio_horario = document.getElementById("horaInicioHorario").value;
    let hora_fin_horario = document.getElementById("horaFinHorario").value;
    let tipo_horario = document.getElementById("tipoHorario").value; // clase o recreo

    if (!verificarHora(hora_inicio_horario) || !verificarHora(hora_fin_horario)) {
        e.preventDefault();
        return;
    }
    if (!validarHorario(tipo_horario, hora_inicio_horario, hora_fin_horario)) {
        e.preventDefault();
        return;
    }
    if (haySolapamiento(hora_inicio_horario, hora_fin_horario, horariosExistentes)) {
        alert("El horario se solapa con otra clase o recreo.");
        e.preventDefault();
        return;
    }
});



// Ejemplo de uso en el formulario de horarios
let horariosExistentes = [
    // {inicio: "07:00", fin: "07:45", tipo: "clase"},
    // {inicio: "07:45", fin: "07:50", tipo: "recreo"}
];

let formulario_dependencias = document.querySelector(".dependencias-form");
formulario_dependencias.addEventListener("submit", function(e) {
    
});

//Se hace la funcion del tipo de dato que queremos verificar, entre los parentesis
//se toman como argumento las siguientes variables: nombre (la string a verificar) y
//tipoDato (si el dato es un nombre, ese string seria 'nombre', si fuera apellido seria
// 'apellido').
function verificarString(nombre, tipoDato){

    //El regex son los caracteres que queremos verificar que nuestra string NO tenga.
    //Regex es una expresion que se marca con //, por eso ese caracter a veces esta repetido
    let regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/; 

    if (nombre.length <= 2 || nombre.length >= 40) {
        alerta_fallo("El " + tipoDato + " ingresado debe tener entre 2 y 40 caracteres");
        return false; //Se devuelve false para que el formulario NO se envie y se muestre la alerta
    } else {

        //Le decimos al regex que comience desde el principio de su elemento a investigar
        //para evitar errores
        regex.lastIndex = 0; 
        if (!regex.test(nombre)){
        alerta_fallo("El " + tipoDato + " ingresado no puede contener caracteres especiales");
        return false;
    } 
    }
    return true; //Si esta todo bien, devuelve TRUE
}

function verificarEmail(email) {
    let regex = /^[a-zA-Z0-9.]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,}$/;
    if (!regex.test(email)) {
        alerta_fallo("El email ingresado no es válido");
        return false;
    }

    if (email.length < 5 || email.length > 50) {
        alerta_fallo("El email ingresado debe tener entre 5 y 50 caracteres");
        return false;
    }
    return true;
}

function verificarFechanacimiento(fecha) {
    let regex = /^\d{4}-\d{2}-\d{2}$/;
    if (!regex.test(fecha)) {
        alerta_fallo("La fecha de nacimiento ingresada no es válida");
        return false;
    }

    // Calcular edad
    let fechaActual = new Date();
    let nacimiento = new Date(fecha);
    let edad = fechaActual.getFullYear() - nacimiento.getFullYear();
    let m = fechaActual.getMonth() - nacimiento.getMonth();
    if (m < 0 || (m === 0 && fechaActual.getDate() < nacimiento.getDate())) {
        edad--;
    }

    if (edad < 18 || edad > 100) {
        alerta_fallo("La edad debe estar entre 18 y 100 años");
        return false;
    }


    return true;
}

function verificarDireccion(direccion) {
    if (direccion.length < 5 || direccion.length > 100) {
        alerta_fallo("La dirección ingresada debe tener entre 5 y 100 caracteres");
        return false;
    }
    return true;
}

function verificarHora(hora) {
    let regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
    if (!regex.test(hora)) {
        alerta_fallo("La hora ingresada no es válida");
        return false;
    }
    return true;
}

// Validar rango y duración según tipo
function validarHorario(tipo, horaInicio, horaFin) {
    function aMinutos(hora) {
        let [h, m] = hora.split(':').map(Number);
        return h * 60 + m;
    }
    let inicio = aMinutos(horaInicio);
    let fin = aMinutos(horaFin);

    if (tipo === "clase") {
        let minInicio = aMinutos("07:00");
        let maxFin = aMinutos("17:00");
        if (inicio < minInicio || fin > maxFin) {
            alert("Las clases deben estar entre 07:00 y 17:00");
            return false;
        }
        if (fin - inicio !== 45) {
            alert("Cada clase debe durar exactamente 45 minutos");
            return false;
        }
    } else if (tipo === "recreo") {
        if (fin - inicio !== 5) {
            alert("El recreo debe durar exactamente 5 minutos");
            return false;
        }
    }
    return true;
}

// Verificar solapamiento de horarios
function haySolapamiento(nuevoInicio, nuevoFin, horariosExistentes) {
    function aMinutos(hora) {
        let [h, m] = hora.split(':').map(Number);
        return h * 60 + m;
    }
    let inicioNuevo = aMinutos(nuevoInicio);
    let finNuevo = aMinutos(nuevoFin);

    for (let horario of horariosExistentes) {
        let inicioExistente = aMinutos(horario.inicio);
        let finExistente = aMinutos(horario.fin);
        if (inicioNuevo < finExistente && finNuevo > inicioExistente) {
            return true;
        }
    }
    return false;
}

//---------------FUNCIONES DE ALERTAS---------------//
function alerta_success(mensaje, ventana_a_redirigir, tipo_dato){
        Swal.fire({
            title: "Exito!",
            text: mensaje,
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ver " + tipo_dato,
            cancelButtonText: "OK"
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = ventana_a_redirigir;
            }
        });
}

function alerta_fallo(mensaje){
            Swal.fire({
                title: "Ups...",
                text: mensaje,
                icon: "error"
            });

}

function verificarNombreEspacio(nombre) {
    // Permite letras, números, espacios y tildes
    let regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/;
    if (nombre.length < 2 || nombre.length > 40) {
        alerta_fallo("El nombre del espacio debe tener entre 2 y 40 caracteres");
        return false;
    }
    if (!regex.test(nombre)) {
        alerta_fallo("El nombre del espacio solo puede contener letras, números y espacios");
        return false;
    }
    return true;
}

function verificarCapacidad(capacidad) {
    let regex = /^\d+$/;
    if (!regex.test(capacidad)) {
        alerta_fallo("La capacidad ingresada no es válida");
        return false;
    }

    if (capacidad < 1 || capacidad > 40) {
        alerta_fallo("La capacidad debe estar entre 1 y 40");
        return false;
    }
    return true;
}

function verificarNombreEspecial(nombre) {
    // Permite letras, números, espacios, tildes, guiones y símbolo de grado
    let regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-°]+$/;
    if (nombre.length < 2 || nombre.length > 40) {
        alerta_fallo("El nombre debe tener entre 2 y 40 caracteres");
        return false;
    }
    if (!regex.test(nombre)) {
        alerta_fallo("El nombre solo puede contener letras, números, espacios, guiones (-) y el símbolo °");
        return false;
    }
    return true;
}