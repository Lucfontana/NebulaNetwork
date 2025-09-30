<?php
// Inicia la sesión para guardar datos entre páginas, como el idioma seleccionado
session_start();

// Determina el idioma a usar:
// 1) Primero revisa si se pasa por la URL ($_GET['lang'])
// 2) Si no, usa el idioma guardado en la sesión
// 3) Si no hay ninguno, por defecto es español ("es")
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'es');
$_SESSION['lang'] = $lang;

// Carga las traducciones desde el archivo JSON y las convierte en un array asociativo
$langFile = file_get_contents(__DIR__ . "/lang.json");
$translations = json_decode($langFile, true);

// Función para traducir texto según la clave $key y el idioma actual
// Opcionalmente reemplaza {user} si se pasa un nombre
if (!function_exists('t')) {
    function t($key, $user = null) {
        global $translations, $lang;
        $text = $translations[$lang][$key] ?? $key; // si no existe la clave, devuelve la propia clave
        if ($user) {
            $text = str_replace("{user}", $user, $text);
        }
        return $text;
    }
}
?>
