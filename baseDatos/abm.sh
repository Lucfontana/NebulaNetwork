#!/bin/bash

#Primero se declaran todas las funciones, ya que el codigo se va ejecutando linea por linea
continuar_o_salir(){
    echo ''
    echo "Desea continuar o salir? (1- Continuar; Cualquier otro caracter para salir)" #echo: Muestra un mensaje
    read opc #read: Lee la entrada del usuario

    case "$opc" in #Case es lo mismo que el switch en cualquier otro lenguaje; se indica case y la variable a analizar
        1) menu
            ;;
        *) exit 
            ;;
    esac

}

#Funcion para ingesar profesores 
ingresar_profesor() {
    #Se declaran los parametros que van a usarse con su debido orden 

    #Los parametros se declaran sin espacios entre el nombre de la variable y el orden
    local ci=$1
    local pass=$2
    local nombre=$3
    local apellido=$4
    local email=$5
    local fecha=$6
    local direccion=$7
    local nombre_db=$8
    local usuario_db=$9
    local contra_db=${10} #Para valores despues del 9, se lo pone entre {}

    #La query todo en la misma linea porque se puede romper si se separa (podria acomodarse con EOF)
    query="INSERT INTO profesores (ci_profesor, pass_profesor, nombre, apellido, email, fecha_nac, direccion) VALUES ('$ci', '$pass', '$nombre', '$apellido', '$email', '$fecha', '$direccion');"

    #  -h indica donde esta el host
    #  -p es la contrasenia
    #  el atributo sguiente a la contrasena es el nombre de la base de datos
    #  -e Indica la consulta a ejecutar en la bd
    mysql -h localhost -u "$usuario_db" -p"$contra_db" "$nombre_db" -e "$query"

    #$? tiene el estado de exito del ultimo comando ejecutado
    #si $? es igual a 0 (-eq) significa que la operacion fue exitosa
    if [ $? -eq 0 ]; then
        echo "Profesor agregado correctamente."
        continuar_o_salir #Se llama a la funcion coninuar_o_salir, que pregunta al usuario si quiere realizar otra accion o salir del sistema
    else #si $? tiene otro valor significa que hubo un error, por lo que se muestra mensaje de error
        echo "Error al registrar al profesor; vuelva a intentarlo"
        continuar_o_salir #Llamar funcion continuar_o_salir para lo mismo q arriba
    fi

} 

#Mismo funcionamiento q arriba
Eliminar() {
    local usuario_db=$1
    local contra_db=$2
    local nombre_db=$3
    local ci=$4

    mysql -u"$usuario_db" -p"$contra_db" "$nombre_db" -e "DELETE FROM profesores WHERE ci_profesor=$ci;"
    if [ $? -eq 0 ]; then
        echo "Profesor con CI $ci eliminado correctamente."
        continuar_o_salir
        else
        echo "Error al eliminar profesor con CI $ci."
        continuar_o_salir
    fi
}

editar(){
    #Declarar los parametros que son enviados
    local ci=$1
    local nombre=$2
    local usuario_db=$3
    local contra_db=$4
    local nombre_db=$5

    #Query para verificar si el profesor existe
    query_verificar_profe="SELECT COUNT(*) FROM profesores WHERE ci_profesor='$ci';"

    #Query para actualizar el nombre del profesor
    query_editar="UPDATE profesores SET nombre = '$nombre' WHERE ci_profesor='$ci';"

    #Primero, se ejecuta la consulta para verificar si el profesor existe
    #-N hace que nosalga el nombre de las columnas en el resultado (porque el resultado de un select viene con el formato de columpa y etc etc)
    #-s remueve todos los bordes y espacios (todos los caracteres adicionales) (silent mode)
    filas=$(mysql -N -s -h localhost -u "$usuario_db" -p"$contra_db" "$nombre_db" -e "$query_verificar_profe")

    #Si la query se pudo ejecutar significa que no existe el profe ingresado
    if [ "$filas" -eq 0 ]; then
        echo "Error: El profesor con CI $ci no existe."
        continuar_o_salir #se consulta al usuario si quiere seguir con la ejecucion del programa o no
        return #se termina la ejecucion aca (SOLAMENTE Si el profesor NO EXISTE)
    fi

    #Como el codigo siguio significa q esta todo bien, por lo que se actualiza el nombre 
    mysql -h localhost -u "$usuario_db" -p"$contra_db" "$nombre_db" -e "$query_editar"

    if [ $? -eq 0 ]; then
        echo "Nombre modificado correctamente"
        continuar_o_salir
    else
        echo "Hubo un error al actualizar la informacion"
        continuar_o_salir
    fi
}

mostrar_profes(){
    local usuario_db=$1
    local contra_db=$2
    local nombre_db=$3

    query="SELECT * FROM profesores"
    mysql -h localhost -u "$usuario_db" -p"$contra_db" "$nombre_db" -e "$query"

    continuar_o_salir

}

#Se declara el menu para ser cargado nuevamente dependiendo de continuar_o_salir
menu(){
clear #Se limpia todo lo que estaba antes en la consola cada vez que es llamada la funcion
echo "=================================="
echo "Administracion de NEBULANETWORK DB"
echo "Ingrese una opcion: "
echo "1- Registrar Profesores"
echo "2- Eliminar profesores"
echo "3- Modificar nombre profesor"
echo "4- Mostrar tabla profesores"
echo "5- Salir"
echo "=================================="
read opc

nombre_db="nebulanetwork"
usuario_db="root"
contra_db=""

case "$opc" in 
    1)
        echo "Ingrese la CI del profesor: "
        read CI

        echo "Ingrese la contrasena (igual a la CI): "
        read pass

        echo "Ingrese el nombre: "
        read nombre

        echo "Ingrese el apellido: "
        read apellido

        echo "Ingrese el email: "
        read email

        echo "Ingrese la fecha de nacimiento (Anio-mes-dia) "
        read fecha

        echo "Ingrese la direccion: "
        read direccion

        #Se llama a la funcion 
        ingresar_profesor "$CI" "$pass" "$nombre" "$apellido" "$email" "$fecha" "$direccion" "$nombre_db" "$usuario_db" "$contra_db"
        ;;
    2)
        echo "Ingrese la CI del profesor a eliminar: "
        read CI
        Eliminar "$usuario_db" "$contra_db" "$nombre_db" "$CI"
        ;;
    3)
        echo "Ingrese la CI del profesor a editar"
        read CI

        echo "Ingrese el nuevo nombre"
        read nombre

        editar "$CI" "$nombre" "$usuario_db" "$contra_db" "$nombre_db"
        ;;
    4)
        mostrar_profes "$usuario_db" "$contra_db" "$nombre_db"
        ;;

    5)
        exit;
        ;;
    *)
        echo "Opcion invalida"
        continuar_o_salir
        ;;
esac
}

menu
