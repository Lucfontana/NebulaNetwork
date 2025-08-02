const button = document.getElementById("sideButton");
const aside = document.getElementById("aside");
const linkAsideText = document.getElementById("link-aside-text");
const linkAsideImages = document.getElementById("link-aside-images");
const mainContent = document.getElementById("contenido");



button.addEventListener("click", ()=> {
    aside.classList.toggle("activo");
    linkAsideText.classList.toggle("activo");
    linkAsideImages.classList.toggle("activo");
    mainContent.classList.toggle("activo");
})

