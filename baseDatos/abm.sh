 #!/bin/bash
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


ingresar_profesor() {
    local ci = $1
    local pass = $2
    local nombre = $3
    local apellido = $4
    local email = $5
    local fecha = $6
    local direccion = $7

    
} 