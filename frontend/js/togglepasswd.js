const eyes = document.querySelectorAll(".togglePassword");;

eyes.forEach(eye => {
  eye.addEventListener('click', function () {
    // busca el input hermano más cercano
    const input = this.previousElementSibling;
    // si el tipo del input es passwd lo pasa a texto para que se muestre y si es otro a password, se le aplica el tipo passowd
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    //le carga el tipo de arriba
    input.setAttribute('type', type);

    //cambia el icono
    this.classList.toggle('fa-eye-slash');
  });
});