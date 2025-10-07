const navbar = document.getElementById('navbarText');
const abrir = document.getElementById("desplegar");


abrir.addEventListener("click", () => {
    if (!navbar.classList.contains("show")) {
        navbar.classList.add("show");
    }
})

abrir.addEventListener("click", () => {
    if (navbar.classList.contains("show")) {
        navbar.classList.remove("show");
    }
})