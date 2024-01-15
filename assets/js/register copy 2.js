document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-1");
  const mailInput = form.querySelector('[name="mail"]');
  const userInput = form.querySelector('[name="user"]');
  const passInput = form.querySelector('[name="pass"]');
  const pass2Input = form.querySelector('[name="pass2"]');

  mailInput.addEventListener("keydown", validateEmail);
  userInput.addEventListener("keydown", validateUser);
  passInput.addEventListener("keydown", validatePass);
  pass2Input.addEventListener("keydown", validatePass2);
  form.addEventListener("submit", validateForm);
  function validateEmail() {
    const errorSpan = document.getElementById("error-mail");
    if (!isValidEmail(mailInput.value)) {
      mailInput.classList.add("border-red-500");
      errorSpan.textContent = "El correo electrónico no es válido.";
      errorSpan.classList.remove("hidden error");
    } else {
      mailInput.classList.remove("border-red-500");
      errorSpan.classList.add("hidden error");
    }
  }

  function validateUser() {
    const errorSpan = document.getElementById("error-user");
    if (!isValidUser(userInput.value)) {
      userInput.classList.add("border-red-500");
      errorSpan.textContent = "El nombre de usuario no es válido.";
      errorSpan.classList.remove("hidden error");
    } else {
      userInput.classList.remove("border-red-500");
      errorSpan.classList.add("hidden error");
    }
  }

  function validatePass() {
    const errorSpan = document.getElementById("error-pass");
    if (passInput.value.length < 6) {
      passInput.classList.add("border-red-500");
      errorSpan.textContent = "La contraseña debe tener al menos 6 caracteres.";
      errorSpan.classList.remove("hidden error");
    } else {
      errorSpan.textContent = " ";
      passInput.classList.remove("border-red-500");
      errorSpan.classList.add("hidden error");
    }
  }

  function validatePass2() {
    const errorSpan = document.getElementById("error-pass2");
    if (pass2Input.value !== passInput.value) {
      pass2Input.classList.add("border-red-500");
      errorSpan.textContent = "Las contraseñas no coinciden.";
      errorSpan.classList.remove("hidden error");
    } else {
      pass2Input.classList.remove("border-red-500");
      errorSpan.classList.add("hidden error");
    }
  }

  function validateForm(event) {
    event.preventDefault();

    if (!isValidEmail(mailInput.value)) {
      mailInput.classList.add("border-red-500");
    }

    if (userInput.value.length < 1) {
      userInput.classList.add("border-red-500");
    }

    if (passInput.value.length < 6) {
      passInput.classList.add("border-red-500");
    }

    if (pass2Input.value !== passInput.value) {
      pass2Input.classList.add("border-red-500");
    }

    if (form.querySelectorAll(".border-red-500").length === 0) {
      // Todos los campos son válidos, enviar el formulario
      form.submit();
    } else {
      // Mostrar mensaje de error
      errorText.textContent =
        "Por favor, complete todos los campos correctamente.";
      errorContainer.style.display = "block";
    }
  }

  function isValidEmail(email) {
    return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email);
  }

  function isValidUser(username) {
    // Agrega aquí tu lógica de validación del usuario
    // Ejemplo: return username.length > 0;
  }
});
