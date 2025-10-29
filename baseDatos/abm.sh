#!/bin/bash
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
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

} 

<<<<<<< Updated upstream
Eliminar() {
    local usuario_db=$1
    local contra_db=$2
    local nombre_db=$3
    local ci=$4

    mysql -u"$usuario_db" -p"$contra_db" "$nombre_db" -e "DELETE FROM profesores WHERE ci_profesor=$ci;"
    if [ $? -eq 0 ]; then
        echo "Profesor con CI $ci eliminado correctamente."
        else
        echo "Error al eliminar profesor con CI $ci."
    fi
}
=======
>>>>>>> Stashed changes
echo "=================================="
echo "Administracion de NEBULANETWORK DB"
echo "Ingrese una opcion: "
echo "1- Registrar Profesores"
echo "2- Eliminar profesores"
echo "3- Modificar nombre profesor"
echo "4- Salir"
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
        Eliminar "$usuario_db" "$contra_db" "$nommbre_db" "$CI"
        ;;
    3)
        echo "Opcion 3"
        ;;
    4)
        exit;
        ;;
    *)
        echo "Opcion invalida"
        ;;
esac
