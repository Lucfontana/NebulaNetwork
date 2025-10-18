const eyes = document.querySelectorAll(".togglePassword");;

eyes.forEach(eye => {
  eye.addEventListener('click', function () {
    // busca el input hermano más cercano
    const input = this.previousElementSibling;
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);

    this.classList.toggle('fa-eye-slash');
  });
});