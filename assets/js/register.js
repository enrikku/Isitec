document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-register");
  const mailInput = form.querySelector('[name="mail"]');
  const userInput = form.querySelector('[name="user"]');
  const passInput = form.querySelector('[name="pass"]');
  const pass2Input = form.querySelector('[name="pass2"]');
  const submitButton = document.getElementById("registerBtn");

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
