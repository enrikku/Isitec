document.addEventListener("DOMContentLoaded", function () {
  var userMenuButton = document.getElementById("user-menu-button");
  var userMenu = document.querySelector(".absolute.right-0");

  userMenu.classList.add("hidden");

  userMenuButton.addEventListener("click", function () {
    userMenu.classList.toggle("hidden");
  });

  document.addEventListener("click", function (event) {
    if (
      !userMenu.contains(event.target) &&
      !userMenuButton.contains(event.target)
    ) {
      userMenu.classList.add("hidden");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const mobileMenuButton = document.getElementById("mobile-menu-button");
  const mobileMenu = document.getElementById("mobile-menu");

  mobileMenuButton.addEventListener("click", function () {
    mobileMenu.classList.toggle("hidden");
  });
});
