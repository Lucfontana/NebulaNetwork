
export function verificarCI (CI) {
   if (/^\d{8}$/.test(CI)) {
    return true;
   } else {
    return false;
   }
}
export function verificarExistenciaCI(CI, multiplicadorACadaUno){

    let digitos_CI_en_int = CIaArreglo(CI);
    let digitoVerif = mostrarDigVerificador(CI, multiplicadorACadaUno)


    //Si el ultimo numero de la CI es el mismo que el digitoVerificador, la cédula existe.
    if (digitos_CI_en_int[7] == digitoVerif) {
        return true;
    } else {
        return false;
    }
}

export function mostrarDigVerificador(CI, multiplicadorACadaUno){
    let digitoVerif;
    let resultadoFinal = 0;
    let restador = 0
    let resultadoActual = 0;

    let digitos_CI_en_int = CIaArreglo(CI);


    //Va realizando los calculos y los guarda en la variable resultadoFinal
    //el primer elemento de digitos_CI_en_int multiplicado el primer ele 
    for (let i = 0; i < 7; i++){
        resultadoActual = digitos_CI_en_int[i] * multiplicadorACadaUno[i];
        
        resultadoFinal = resultadoFinal + resultadoActual;
        console.log('Final' + resultadoFinal);
    }

    //Busca el siguiente multiplo de 10 que sea mayor al resultadoFinal
    for (let i = 0; restador < resultadoFinal; i++){
        restador = 10 * i;
        console.log('Restador: ' + restador);
    }

    //Obtiene el digito verificador
    digitoVerif = restador - resultadoFinal;
    console.log('Digito verificador: ' + digitoVerif );
    return digitoVerif;
}

    //Para dividir la CI en un arreglo con numeros independientes,
    //la funcion 'split' toma como argumento un string, por lo que
    //hay que pasar la CI a string, se divide y despues se pasa
    //a numeros nuevamente
export function CIaArreglo(CI){
    let cadenaCI = CI.toString();
    let digitosCIDivididos = cadenaCI.split('');
    let digitos_CI_en_int = digitosCIDivididos.map(Number);
    return digitos_CI_en_int;
}