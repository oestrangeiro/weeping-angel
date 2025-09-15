const hamburguer = document.querySelector("#toggle-btn");

hamburguer.addEventListener("click", function () {
    console.log("click");
    document.querySelector("#sidebar").classList.toggle("expand");
});