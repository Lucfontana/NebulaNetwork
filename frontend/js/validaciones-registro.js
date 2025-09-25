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
    let nombre_profesor = document.getElementById("nombreProfesor").value;
    let apellido_profesor = document.getElementById("apellidoProfesor").value;
    let ci_profesor = document.getElementById("ciProfesor").value;

    if (!verificarString(nombre_profesor, "nombre")) {
        e.preventDefault();
    }
    if (!verificarString(apellido_profesor, "apellido")) {
        e.preventDefault();
    }
    if (!verificarCI(ci_profesor)) {
        e.preventDefault();
    }
});

function verificaremail(email) {
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!regex.test(email)) {
        alert("El email ingresado no es válido");
        return false;
    }
    return true;
}
