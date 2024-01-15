document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-1");
  const mailInput = form.querySelector('[name="mail"]');
  const userInput = form.querySelector('[name="user"]');
  const passInput = form.querySelector('[name="pass"]');
  const pass2Input = form.querySelector('[name="pass2"]');
  const errorText = document.getElementById("error-text");
  const errorContainer = document.getElementById("error-container");

  mailInput.addEventListener("input", validateEmail);
  userInput.addEventListener("input", validateUser);
  passInput.addEventListener("input", validatePass);
  pass2Input.addEventListener("input", validatePass2);
  form.addEventListener("submit", validateForm);

  function isValidEmail(email) {
    var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
  }
  function validateEmail() {
    const errorSpan = document.getElementById("error-mail");
    console.log(mailInput.value);
    if (!isValidEmail(mailInput.value)) {
      mailInput.classList.add("border-red-500");
      errorSpan.textContent = "El correo electrónico no es válido.";
      errorSpan.classList.remove("hidden");
    } else {
      mailInput.classList.remove("border-red-500");
      errorSpan.classList.add("hidden");
    }
  }

  function validateUser() {
    const errorSpan = document.getElementById("error-user");
    if (!isValidUser(userInput.value)) {
      userInput.classList.add("border-red-500");
      errorSpan.textContent = "El nombre de usuario no es válido.";
      errorSpan.classList.remove("hidden");
    } else {
      userInput.classList.remove("border-red-500");
      errorSpan.classList.add("hidden");
    }
  }

  function validatePass() {
    const errorSpan = document.getElementById("error-pass");

    if (passInput.value.length <= 6) {
      console.log(passInput.value.length);
      passInput.classList.remove("border-gray-300");
      /* passInput.classList.add("border-red-500"); */
      errorSpan.textContent = "La contraseña debe tener al menos 6 caracteres";
      errorSpan.classList.remove("hidden error");
    } else {
      passInput.classList.add("border-green-500");
      errorSpan.textContent = "";
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
    } else {
      mailInput.classList.remove("border-red-500");
    }

    if (userInput.value.length < 1) {
      userInput.classList.add("border-red-500");
    } else {
      userInput.classList.remove("border-red-500");
    }

    if (passInput.value.length < 6) {
      passInput.classList.add("border-red-500");
    } else {
      passInput.classList.remove("border-red-500");
    }

    if (pass2Input.value !== passInput.value) {
      pass2Input.classList.add("border-red-500");
    } else {
      pass2Input.classList.remove("border-red-500");
    }

    if (
      !mailInput.classList.contains("border-red-500") &&
      !userInput.classList.contains("border-red-500") &&
      !passInput.classList.contains("border-red-500") &&
      !pass2Input.classList.contains("border-red-500")
    ) {
      // Todos los campos son válidos, enviar el formulario
      form.submit();
    } else {
      // Mostrar mensaje de error
      errorText.textContent =
        "Por favor, complete todos los campos correctamente.";
      errorContainer.style.display = "block";
    }
  }
});
