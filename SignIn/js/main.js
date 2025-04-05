let icon = document.querySelector(".fa-solid");
let password = document.querySelector(".password");
let email = document.querySelector(".email");
icon.addEventListener("click", () => {
  if (password.type == "password") {
    password.type = "text";
    icon.setAttribute("class", "fa-solid fa-eye-slash");
  } else {
    password.type = "password";
    icon.setAttribute("class", "fa-solid fa-eye");
  }
});
password.addEventListener("input", () => {
  if (password.value.length > 0) {
    icon.style.display = "block";
  } else {
    icon.style.display = "none";
  }
  //   console.log(password.value.length);
});

// ==============
