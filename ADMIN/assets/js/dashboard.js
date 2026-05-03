const dotButtons = document.querySelectorAll(".dot-btn");

dotButtons.forEach(button => {
    button.addEventListener("click", () => {
        alert("Menu aksi pesanan diklik");
    });
});

const filterBtn = document.querySelector(".panel-actions button:nth-child(2)");

if (filterBtn) {
    filterBtn.addEventListener("click", () => {
        alert("Fitur filter pesanan");
    });
}