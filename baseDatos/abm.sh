#!/bin/bash
echo "=================================="
echo "Administracion de NEBULANETWORK DB"
echo "Ingrese una opcion: "
echo "1- Registrar Profesores"
echo "2- Eliminar profesores"
echo "3- Modificar nombre profesor"
echo "4- Salir"
echo "=================================="
read opc


nombre_db = "nebulanetwork"
usuario_db = "root"
contra_db = ""

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
        ;;
    2)
        echo "Opcion 2"
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


ingresar_profesor() {
    local ci = $1
    local pass = $2
    local nombre = $3
    local apellido = $4
    local email = $5
    local fecha = $6
    local direccion = $7

    
} 