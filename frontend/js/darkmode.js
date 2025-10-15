const btnBienvenida = document.getElementsByClassName("btn-bienvenida");
const elementos = document.querySelectorAll('.nav-link, .navbar-brand, .nav-item a, .navStyle');
const navstyle = document.getElementById('nav');
const aside = document.querySelector('aside');
const darkmodeIcon = document.querySelector('#darkmode-icon'); // ✅ corregido

darkmodeIcon.addEventListener('click', () => {
    console.log('Dark mode activado'); // Verifica que el evento se dispare
    Array.from(btnBienvenida).forEach(btnDienvenido => {
        btnDienvenido.classList.toggle("darkmode");
    });
    Array.from(elementos).forEach(elemento => {
        elemento.classList.toggle('darkmode');
    });
    navstyle.classList.toggle('darkmode');
    aside.classList.toggle('darkmode');
});