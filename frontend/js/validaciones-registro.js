//-----FORMULARIOS-----//

//Por cada formulario, se lo debe llamar (cuando se envia) y verificar que todos sus
//campos esten limpios y correctos

                                    //querySelector busca y agarra el primer elemento que
                                    //coincida con selector especifico

                                    //"¿Y porque no buscar por la id? no quedamas facil?"
                                    //no pq todos los formularios tienen la misma id y eso afectaria el
                                    //register-modal que hizo lautaro (CREO, si quieren prueben)
let formulario_asignaturas = document.querySelector(".asignatura-form");

formulario_asignaturas.addEventListener("submit", function(e) {
    //Aca traes todos los valores del formulario, asignaturas solo tiene uno entonces se trae uno
    let nombre_asignatura = document.getElementById("nombreAsignatura").value;

    //Aca, uno se fijaria que TODAS las funciones llamadas sean FALSE y previene el envio del formulario
    //para que se muestre la alerta de error (en el caso de las asignaturas solo hay un campo, 
    //por lo que se SOLO se verifica que el nombre sea correcto).

    //PERO, por ejemplo en profesores, deberias verificar la CI, la contrasena, nombre, apellido, etcetc y
    //saber que todos son FALSE para que el formulario NO se envie
    if (!verificarString(nombre_asignatura, "nombre")){ // entre comillas ponemos "nombre" porque es lo que validamos, 
                                                        //Si verificaramos "apellido" ahi adentro iria apellido
        e.preventDefault(); //Se previene el envio del formulario 
    }
});

//------------------VALIDACIONES-------------------//
//-------------------------------------------------//
//Nota: Las validaciones de CI ya estan creadas en el archivo de 'login.js', quedaria importar el archivo y las funciones

//Verificar en el documento de Drive de "Tareas pendientes PROYECTO" las distintas funciones que hay para hacer

//Se hace la funcion del tipo de dato que queremos verificar, entre los parentesis
//se toman como argumento las siguientes variables: nombre (la string a verificar) y
//tipoDato (si el dato es un nombre, ese string seria 'nombre', si fuera apellido seria
// 'apellido').
function verificarString(nombre, tipoDato){

    //El regex son los caracteres que queremos verificar que nuestra string NO tenga.
    //Regex es una expresion que se marca con //, por eso ese caracter a veces esta repetido
    let regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/; 

    if (nombre.length <= 2 || nombre.length >= 40) {
        alert("El " + tipoDato + " ingresado debe tener entre 2 y 40 caracteres");
        return false; //Se devuelve false para que el formulario NO se envie y se muestre la alerta
    } else {

        //Le decimos al regex que comience desde el principio de su elemento a investigar
        //para evitar errores
        regex.lastIndex = 0; 
        if (!regex.test(nombre)){
        alert("El " + tipoDato + " ingresado no puede contener caracteres especiales");  
        return false;
    } 
    }
    return true; //Si esta todo bien, devuelve TRUE
}


let formulario_profesores = document.querySelector(".profesor-form");

formulario_profesores.addEventListener("submit", function(e) {
    let ci_profesor = document.getElementById("ciProfesor").value;
    let nombre_profesor = document.getElementById("nombreProfesor").value;
    let apellido_profesor = document.getElementById("apellidoProfesor").value;
    let email_profesor = document.getElementById("emailProfesor").value;
    let fecha_nacimiento_profesor = document.getElementById("fechaNacimientoProfesor").value;
    let direccion_profesor = document.getElementById("direccionProfesor").value;

    if (!verificarCI(ci_profesor)) {
        e.preventDefault();
    }
    if (!verificarString(nombre_profesor, "nombre")) {
        e.preventDefault();
    }
    if (!verificarString(apellido_profesor, "apellido")) {
        e.preventDefault(); 
    }
    if (!verificarEmail(email_profesor)) {
        e.preventDefault();
    }
    if (!verificarFechanacimiento(fecha_nacimiento_profesor)) {
        e.preventDefault();
    }
    if (!verificarDireccion(direccion_profesor)) {
        e.preventDefault();
    }
});

function verificarEmail(email) {
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!regex.test(email)) {
        alert("El email ingresado no es válido");
        return false;
    }

    if (email.length < 5 || email.length > 50) {
        alert("El email ingresado debe tener entre 5 y 50 caracteres");
        return false;
    }
    return true;
}

function verificarFechanacimiento(fecha) {
    let regex = /^\d{4}-\d{2}-\d{2}$/;
    if (!regex.test(fecha)) {
        alert("La fecha de nacimiento ingresada no es válida");
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
        alert("La edad debe estar entre 18 y 100 años");
        return false;
    }


    return true;
}

function verificarDireccion(direccion) {
    if (direccion.length < 5 || direccion.length > 100) {
        alert("La dirección ingresada debe tener entre 5 y 100 caracteres");
        return false;
    }
    return true;
}

let formulario_superusuarios = document.querySelector(".superusuarios-form");
formulario_superusuarios.addEventListener("submit", function(e) {
    let ci_superusuario = document.getElementById("ciSuperusuario").value;
    let nombre_superusuario = document.getElementById("nombreSuperusuario").value;
    let apellido_superusuario = document.getElementById("apellidoSuperusuario").value;
    //let email_superusuario = document.getElementById("emailSuperusuario").value;

    if (!verificarCI(ci_superusuario)) {
        e.preventDefault();
    }
    if (!verificarString(nombre_superusuario, "nombre")) {
        e.preventDefault();
    }
    if (!verificarString(apellido_superusuario, "apellido")) {
        e.preventDefault();
    }
});

let formulario_recursos = document.querySelector(".recursos-form");
formulario_recursos.addEventListener("submit", function(e) {
    let nombre_recurso = document.getElementById("nombreRecurso").value;

    if (!verificarString(nombre_recurso, "nombre")) {
        e.preventDefault();
    }

});

let formulario_espacios = document.querySelector(".espacios-form");
formulario_espacios.addEventListener("submit", function(e) {
    let nombre_espacio = document.getElementById("nombreEspacio").value;
    let capacidad_espacio = document.getElementById("capacidadEspacio").value;

    if (!verificarString(nombre_espacio, "nombre")) {
        e.preventDefault();
    }
    if (!verificarCapacidad(capacidad_espacio)) {
        e.preventDefault();
    }
});

function verificarCapacidad(capacidad) {
    let regex = /^\d+$/;
    if (!regex.test(capacidad)) {
        alert("La capacidad ingresada no es válida");
        return false;
    }

    if (capacidad < 1 || capacidad > 40) {
        alert("La capacidad debe estar entre 1 y 40");
        return false;
    }
    return true;
}

let formulario_cursos = document.querySelector(".cursos-form");
formulario_cursos.addEventListener("submit", function(e) {
    let nombre_curso = document.getElementById("nombreCurso").value;
    let capacidad_curso = document.getElementById("capacidadCurso").value;

    if (!verificarString(nombre_curso, "nombre")) {
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

    if (!verificarHora(hora_inicio_horario)) {
        e.preventDefault();
    }
    if (!verificarHora(hora_fin_horario)) {
        e.preventDefault();
    }
});

function verificarHora(hora) {
    let regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
    if (!regex.test(hora)) {
        alert("La hora ingresada no es válida");
        return false;
    }
    return true;
}

// Nueva función para validar el rango y duración
function validarHorario(tipo, horaInicio, horaFin) {
    // Convertir a minutos desde las 00:00
    function aMinutos(hora) {
        let [h, m] = hora.split(':').map(Number);
        return h * 60 + m;
    }
    let inicio = aMinutos(horaInicio);
    let fin = aMinutos(horaFin);

    if (tipo === "clase") {
        // Horario permitido: 07:00 a 17:00
        let minInicio = aMinutos("07:00");
        let maxFin = aMinutos("17:0");
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