<?php

if(isset($_POST['value1'])){
    $mensaje = 'Hola! estamos actualizando la pagina con el primer valor!! lol!';
    include 'nueva_pagina.php';
} else if (isset($_POST['value2'])){
    $mensaje = '<p>' . 'Lautaro dejame ver a nuestros hijos...' .  '</p>';
    include 'nueva_pagina.php';
}

?>