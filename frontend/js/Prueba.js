export function verificarNombreEspecial(nombre) {
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


export function verificarCapacidad(capacidad) {
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


export function verificarString(nombre, tipoDato) {


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
        if (!regex.test(nombre)) {
            alerta_fallo("El " + tipoDato + " ingresado no puede contener caracteres especiales");
            return false;
        }
    }
    return true; //Si esta todo bien, devuelve TRUE
}


export function verificarEmail(email) {
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


export function verificarFechanacimiento(fecha) {
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


export function verificarDireccion(direccion) {
    if (direccion.length < 5 || direccion.length > 100) {
        alerta_fallo("La dirección ingresada debe tener entre 5 y 100 caracteres");
        return false;
    }
    return true;
}


export function verificarHora(hora) {
    let regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
    if (!regex.test(hora)) {
        alerta_fallo("La hora ingresada no es válida");
        return false;
    }
    return true;
}


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
export function haySolapamiento(nuevoInicio, nuevoFin, horariosExistentes) {
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


export function verificarNombreEspacio(nombre) {
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




//Alertas


export function alerta_fallo(mensaje) {
    Swal.fire({
        title: "Ups...",
        text: mensaje,
        icon: "error"
    });


}


export function alerta_success_update(mensaje, ventana_a_redirigir) {
    Swal.fire({
        title: "Exito!",
        text: mensaje,
        icon: "success",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ok",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = ventana_a_redirigir;
        }
    });
}

