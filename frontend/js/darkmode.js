const d = document;
const darkmodeIcon = d.querySelector('#darkmode-icon');

let darkModeState = false;

//media query detecta si el usuario tiene el modo oscuro activado
const useDark = window.matchMedia('(prefers-color-scheme: dark)');

//cambiar a modo oscuro
function toggleDarkMode(state) {
    d.documentElement.classList.toggle('darkmode', state);
    darkModeState = state;

}

//escuchar cambios en la preferencia del usuario
function setDarkModeLocalStorage(state) {
    localStorage.setItem('darkMode', state);
}

//configuracion inicial
toggleDarkMode(useDark.matches);
toggleDarkMode(localStorage.getItem('darkMode') == 'true');

useDark.addListener((event) => toggleDarkMode(event.matches));

//evento click en el icono y establece dentro del localstorage
darkmodeIcon.addEventListener('click', () => {
    darkModeState = !darkModeState;

    toggleDarkMode(darkModeState);
    setDarkModeLocalStorage(darkModeState);
});