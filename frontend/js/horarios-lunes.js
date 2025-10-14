function cambiarCurso(idCurso) {
    if (idCurso && idCurso !== "0") {
        window.location.href = "?curso_id=" + idCurso;
    }
}