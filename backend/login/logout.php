<?php

session_start();
session_unset();//Limpia los datos de la sesión
session_destroy();//Elimina completamente la sesión del servidor.

header("Location: ../../../frontend/index.php");
//Una vez eliminada la sesión, esta línea redirecciona al usuario a la página de inicio

?>