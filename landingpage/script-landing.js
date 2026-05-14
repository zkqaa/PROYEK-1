const addButtons = document.querySelectorAll(".price-row button");

addButtons.forEach(button => {
    button.addEventListener("click", () => {
        alert("Menu berhasil ditambahkan!");
    });
});