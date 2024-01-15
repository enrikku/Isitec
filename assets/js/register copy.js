document.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("form-1")
    .addEventListener("submit", function (event) {
      var form = event.target;
      var mailInput = form.elements["mail"];
      var userInput = form.elements["user"];
      var passInput = form.elements["pass"];
      var pass2Input = form.elements["pass2"];
      var errMsg = "";

      // Validación de correo electrónico
      if (!isValidEmail(mailInput.value)) {
        errMsg += "El correo electrónico no es válido. ";
        mailInput.classList.add("error");
      } else {
        mailInput.classList.remove("error");
      }

      // Validación de usuario
      if (userInput.value.length < 1) {
        errMsg += "El campo de usuario es obligatorio. ";
        userInput.classList.add("error");
      } else {
        userInput.classList.remove("error");
      }

      // Validación de contraseña
      if (passInput.value.length < 6) {
        errMsg += "La contraseña debe tener al menos 6 caracteres. ";
        passInput.classList.add("error");
      } else {
        passInput.classList.remove("error");
      }

      // Validación de confirmación de contraseña
      if (pass2Input.value !== passInput.value) {
        errMsg += "Las contraseñas no coinciden. ";
        pass2Input.classList.add("error");
      } else {
        pass2Input.classList.remove("error");
      }

      // Mostrar mensaje de error si existe
      var errorContainer = document.getElementById("error-container");
      var errorText = document.getElementById("error-text");

      if (errMsg.length > 0) {
        event.preventDefault(); // Evitar envío del formulario
        errorText.textContent = errMsg;
        errorContainer.style.display = "block";
      } else {
        errorContainer.style.display = "none";
      }
    });

  function isValidEmail(email) {
    var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
  }
});
