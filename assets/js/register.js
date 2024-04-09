document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-register");
  const mailInput = form.querySelector('[name="mail"]');
  const userInput = form.querySelector('[name="user"]');
  const passInput = form.querySelector('[name="pass"]');
  const pass2Input = form.querySelector('[name="pass2"]');
  const submitButton = document.getElementById("registerBtn");
  const togglePasswordButton = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("pass");
  const togglePasswordIcon = document.getElementById("togglePasswordIcon");
  const togglePasswordButton2 = document.getElementById("togglePassword2");
  const passwordInput2 = document.getElementById("pass2");
  const togglePasswordIcon2 = document.getElementById("togglePasswordIcon2");

  let passWdOk = false;
  mailInput.addEventListener("input", validateEmail);
  userInput.addEventListener("input", validateUser);
  passInput.addEventListener("input", validatePass);
  pass2Input.addEventListener("input", validatePass2);
  form.addEventListener("submit", validateForm);

  function isValidEmail(email) {
    var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
  }

  function isValidUser(user) {
    return user.length >= 4 && !user.includes("@");
  }

  function isValidPassword(password) {
    return password.length >= 8 && /[A-Z]/.test(password);
  }

  function resetInputStyle(inputElement, labelElement, errorElement, errorMsg) {
    if (inputElement.value.length < 1) {
      errorElement.classList.add("hidden");
      inputElement.classList.remove("focus:border-red-500");
      inputElement.classList.add("focus:border-gray-500");
      labelElement.style.color = "";
    } else {
      errorElement.textContent = errorMsg;
    }
  }

  function borderRed(inputElement, labelElement, errorElement) {
    inputElement.classList.remove(
      "focus:border-gray-500",
      "focus:border-green-500"
    );
    inputElement.classList.add("focus:border-red-500");
    labelElement.style.color = "var(--color-error)";
    errorElement.classList.remove("hidden");
  }

  function borderGreen(inputElement, labelElement, errorElement) {
    inputElement.classList.remove(
      "focus:border-red-500",
      "focus:border-gray-500"
    );
    inputElement.classList.add("focus:border-green-500");
    labelElement.style.color = "var(--color-success)";
    errorElement.classList.add("hidden");
    submitButton.style.backgroundColor = "";
  }

  function validateEmail() {
    const errorSpan = document.getElementById("error-mail");
    const labelMail = document.getElementById("label-mail");
    if (!isValidEmail(mailInput.value)) {
      borderRed(mailInput, labelMail, errorSpan);
      let errorMsg = "El correo electrónico no es válido.";
      resetInputStyle(mailInput, labelMail, errorSpan, errorMsg);
    } else {
      borderGreen(mailInput, labelMail, errorSpan);
    }
  }

  function validateUser() {
    const errorSpan = document.getElementById("error-user");
    const labelUser = document.getElementById("label-user");
    if (!isValidUser(userInput.value)) {
      borderRed(userInput, labelUser, errorSpan);
      let errorMsg = "El nombre de usuario no es válido.";
      resetInputStyle(userInput, labelUser, errorSpan, errorMsg);
    } else {
      borderGreen(userInput, labelUser, errorSpan);
    }
  }

  togglePasswordButton.addEventListener("click", () => {
    const type =
      passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);

    // Cambia el SVG dependiendo del estado
    togglePasswordIcon.innerHTML =
      type === "text"
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
  });

  togglePasswordButton2.addEventListener("click", () => {
    const type =
      passwordInput2.getAttribute("type") === "password" ? "text" : "password";
    passwordInput2.setAttribute("type", type);

    // Cambia el SVG dependiendo del estado
    togglePasswordIcon2.innerHTML =
      type === "text"
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
  });

  function validatePass() {
    const errorSpan = document.getElementById("error-pass");
    const labelPass = document.getElementById("label-pass");
    if (!isValidPassword(passInput.value)) {
      borderRed(passInput, labelPass, errorSpan);
      let errorMsg =
        "La contraseña debe tener al menos 8 caracteres y una letra mayúscula";
      resetInputStyle(passInput, labelPass, errorSpan, errorMsg);
    } else {
      borderGreen(passInput, labelPass, errorSpan);
    }
  }

  function validatePass2() {
    const errorSpan = document.getElementById("error-pass2");
    const labelPass2 = document.getElementById("label-pass2");
    if (
      pass2Input.value === passInput.value &&
      isValidPassword(pass2Input.value)
    ) {
      borderGreen(pass2Input, labelPass2, errorSpan);
      passWdOk = true;
    } else {
      borderRed(pass2Input, labelPass2, errorSpan);
      let errorMsg = "Las contraseñas no coinciden.";
      resetInputStyle(pass2Input, labelPass2, errorSpan, errorMsg);
    }
  }
  function validateForm(event) {
    if (
      !passWdOk ||
      !isValidEmail(mailInput.value) ||
      !isValidUser(userInput.value)
    ) {
      event.preventDefault();
      submitButton.style.backgroundColor = "var(--color-error)";
    }
  }
});
