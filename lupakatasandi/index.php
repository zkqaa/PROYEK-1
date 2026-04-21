<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
    <a href="../login/index.php" class="back">
        <span class="arrow">←</span>
        <span class="text">Kembali</span>
    </a>

    <img src="logo.jpeg" class="logo">

    <h2>Lupa Kata Sandi</h2>
    <div class="subtitle">Buat verifikasi nomor HP kamu</div>

    <form action="lupa.php" method="POST">
        <label>No. Hp</label>
        <input 
            type="text"
            name="nohp" 
            id="nohp"
            placeholder="Masukkan No HP"
            maxlength="13"
            inputmode="numeric"
            pattern="[0-9]*"
            required
        >

        <button type="submit" id="nextBtn" disabled>Selanjutnya</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.body.classList.add("page-enter");
    setTimeout(() => {
        document.body.classList.add("page-enter-active");
    }, 10);

    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const href = this.getAttribute("href");

            document.body.classList.add("page-exit");

            setTimeout(() => {
                window.location.href = href;
            }, 400);
        });
    });

    const input = document.getElementById("nohp");
    const button = document.getElementById("nextBtn");

    input.addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9]/g, '');

        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }

        if (this.value.length >= 12 && this.value.length <= 13) {
            button.disabled = false;
            button.classList.add("active");
        } else {
            button.disabled = true;
            button.classList.remove("active");
        }
    });
});
</script>
</body>
</html>