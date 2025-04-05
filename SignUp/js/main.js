let name = document.querySelector(".name");
let email = document.querySelector(".email");
let password = document.querySelector(".password");
let confirmPassword = document.querySelector(".confirmPassword");
let btnsubmit = document.querySelector(".submit");
let icon = document.querySelectorAll(".fa-solid");
// console.log((icon[0].style.cssText = `display:block;`));

// pattern for name
let namePattern = /^[a-zA-Z]+\s[a-zA-Z]*$/;
name.addEventListener("input", () => {
  if (name.value) {
    if (namePattern.test(name.value)) {
      //   name.style.border = "2px solid green";
      name.style.cssText = `border: 2px solid green;
                            box-shadow: 0 0 5px green;`;
    } else {
      name.style.cssText = `border: 2px solid red;
                            box-shadow: 0 0 5px red;`;
    }
  } else {
    name.style.cssText = `border: 2px solid #7bc5f3;
                          box-shadow:none;`;
  }
});
// pattern for email
let emailPattern = /^[a-z]+\w*@gmail.com$/;
email.addEventListener("input", () => {
  if (email.value) {
    if (emailPattern.test(email.value)) {
      email.style.cssText = `border: 2px solid green;
                            box-shadow: 0 0 5px green;`;
    } else {
      email.style.cssText = `border: 2px solid red;
                            box-shadow: 0 0 5px red;`;
    }
  } else {
    email.style.cssText = `border: 2px solid #7bc5f3;
                          box-shadow:none;`;
  }
});
// // patern pasword
// let pasPattern = /^(\w|\W){8,}$/;
// confirmPassword.addEventListener("input", () => {
//   if (confirmPassword.value !== password.value) {
//     console.log(1);
//   }
// });
// ////// btn events
// btnsubmit.addEventListener("click", () => {
//   if (namePattern.test(name.value) && emailPattern.test(email.value)) {
//     btnsubmit.style.cssText = `pointer-events: none;`;
//   }
// });
// let icon = document.querySelector(".fa-solid");
// let password = document.querySelector(".password");
// let email = document.querySelector(".email");
// icon.addEventListener("click", () => {
//   if (password.type == "password") {
//     password.type = "text";
//     icon.setAttribute("class", "fa-solid fa-eye-slash");
//   } else {
//     password.type = "password";
//     icon.setAttribute("class", "fa-solid fa-eye");
//   }
// });
// password.addEventListener("input", () => {
//   if (password.value.length > 0) {
//     icon.style.display = "block";
//   } else {
//     icon.style.display = "none";
//   }
//   //   console.log(password.value.length);
// });

// // ==============
