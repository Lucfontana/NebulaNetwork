const logout = document.getElementById("logout");
localStorage.setItem("logout-event", Date.now());

if(logout){
logout.addEventListener("click", confirmar_logout);
}

function confirmar_logout() {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Estás a punto de cerrar sesión, ¿Deseas continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Cerrar sesión",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../backend/login/logout.php";
        }
    });
}