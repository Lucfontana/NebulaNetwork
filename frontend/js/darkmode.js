// const btnBienvenida = document.getElementsByClassName("btn-bienvenida");
// const elementos = document.querySelectorAll('.nav-link, .navbar-brand, .nav-item a, .navStyle, .nav-link.darkmode:hover, .navbar-brand.darkmode:hover, .navbar-toggler-icon');
// const navstyle = document.getElementById('nav');
// const aside = document.querySelector('aside');
// const darkmodeIcon = document.querySelector('#darkmode-icon');
// const footer = document.querySelector('footer');

// darkmodeIcon.addEventListener('click', () => {
//     console.log('Dark mode activado'); // Verifica que el evento se dispare
//     Array.from(btnBienvenida).forEach(btnDienvenido => {
//         btnDienvenido.classList.toggle("darkmode");
//     });
//     Array.from(elementos).forEach(elemento => {
//         elemento.classList.toggle('darkmode');
//     });
//     navstyle.classList.toggle('darkmode');
//     aside.classList.toggle('darkmode');
//     footer.classList.toggle('darkmode');
// });

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