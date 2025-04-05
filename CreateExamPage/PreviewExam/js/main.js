$(".menu").click(function () {
  $(".bar-side").slideToggle(500);
});
let overlay = document.querySelector(".overlay");
let overlayUpdate = document.querySelector(".overlay-edit");
$(".fa-xmark").click(() => {
  overlay.style.display = "none";
  overlayUpdate.style.display = "none";
});
$(".addQuation").click(() => {
  overlay.style.display = "block";
});
$(".edit").click(() => {
  overlayUpdate.style.display = "block";
  // console.log(overlay);
  // $(".overlay-edit").style.display = "fixed";
});
