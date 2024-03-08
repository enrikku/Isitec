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


document.querySelectorAll('.like-button').forEach(button => {
  button.addEventListener('click', function() {
      const courseId = this.getAttribute('data-id');
      console.log("Id del curso: " + courseId);

      setTimeout(function() {
          window.location.reload();
      }, 50);


      fetch('./guardar-like.php', {
          method: 'POST',
          body: JSON.stringify({ courseId: courseId }),
          headers: {
              'Content-Type': 'application/json'
          }
      })
      .then(response => {
          if (response.ok) {
              return response.json();
          }
          throw new Error('Error al guardar el like');
      })
      .catch(error => {
          console.error('Error:', error);
      });


  });
});

document.querySelectorAll('.dislike-button').forEach(button => {
  button.addEventListener('click', function() {
      const courseId = this.getAttribute('data-id');
      console.log("Id del curso: " + courseId);

      setTimeout(function() {
          window.location.reload();
      }, 50);


      fetch('./guardar-dislike.php', {
          method: 'POST',
          body: JSON.stringify({ courseId: courseId }),
          headers: {
              'Content-Type': 'application/json'
          }
      })
      .then(response => {
          if (response.ok) {
              return response.json();
          }
          throw new Error('Error al guardar el like');
      })
      .catch(error => {
          console.error('Error:', error);
      });


  });
});