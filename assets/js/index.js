document.addEventListener("DOMContentLoaded", () => {
  const forgotPasswordForm = document.getElementById("forgot-password-form");
  const openModalButton = document.getElementById("open-modal");
  const closeModalButton = document.getElementById("close-modal");
  const modal = document.getElementById("authentication-modal");
  const mailInput = forgotPasswordForm.querySelector('[name="mail"]');

  mailInput.addEventListener("input", validateEmail);

  openModalButton.addEventListener("click", () => {
    console.log("Opening modal");
    modal.classList.remove("hidden", "shadow-drop-exit");
    modal.classList.add("shadow-drop-entrance");
  });

  closeModalButton.addEventListener("click", () => {
    console.log("Closing modal");
    modal.classList.remove("shadow-drop-entrance");
    modal.classList.add("shadow-drop-exit");
  });

  modal.addEventListener("animationend", (event) => {
    console.log("Animation ended:", event.animationName);
    if (event.animationName === "animacionSalida") {
      modal.classList.add("hidden");
    }
  });

  function isValidEmail(email) {
    var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
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
});
