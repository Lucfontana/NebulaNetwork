var id = windows.setInterval((e) => {
    doucment.onmousemove = e() {
        n = 10;
    };
    n--;

    if (n <= 0) {
        alert("Your session has expired, please log in again.");
        //window.location.href = "/logout";
    }
}, 1200);