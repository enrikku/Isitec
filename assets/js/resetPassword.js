document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-reset-password");
  const codeInput = form.querySelector('[name="code"]');
  const passInput = form.querySelector('[name="password"]');
  const pass2Input = form.querySelector('[name="password2"]');
  const submitButton = document.getElementById("resetBtn");

  let passWdOk = false;
  codeInput.addEventListener("input", validateCode);
  passInput.addEventListener("input", validatePass);
  pass2Input.addEventListener("input", validatePass2);
  form.addEventListener("submit", validateForm);

  function isValidPassword(password) {
    return password.length >= 8 && /[A-Z]/.test(password);
  }

  function isValidCode(code) {
    return code.length === 6 && /^[0-9]+$/.test(code);
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
  function validateCode() {
    const errorSpanCode = document.getElementById("error-code");
    const labelPassCode = document.getElementById("label-code");
    if (!isValidCode(codeInput.value)) {
      borderRed(codeInput, labelPassCode, errorSpanCode);
      let errorMsg = "El código debe tener 6 digitos numéricos.";
      resetInputStyle(codeInput, labelPassCode, errorSpanCode, errorMsg);
    } else {
      borderGreen(codeInput, labelPassCode, errorSpanCode);
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
    if (!passWdOk || !isValidCode(codeInput.value)) {
      event.preventDefault();
      submitButton.style.backgroundColor = "var(--color-error)";
    }
  }
});
