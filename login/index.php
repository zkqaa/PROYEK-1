<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>

<div class="container">
    
    <!-- Bagian kiri gambar -->
    <div class="left"></div>

    <!-- Bagian kanan form -->
    <div class="right">
        <img src="logo.jpeg" class="logo">
        <h2>Masuk</h2>

        <form action="login.php" method="POST">

            <label>No. HP</label>
            <input type="text" id="nohp" name="nohp" placeholder="Masukkan nomor HP">

            <label>Kata Sandi</label>
            <div class="password-box">
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi">
                <span class="toggle" onclick="togglePassword()">👁</span>
            </div>

            <button class="masuk-btn" id="masukBtn">
                Masuk
            </button>

        </form>

        <div class="options">
            <label class="remember">
                <input type="checkbox"> Ingatkan Aku
            </label>
            <a href="../lupakatasandi/index.php">Lupa Kata Sandi</a>
        </div>

        <div class="divider">Atau</div>

        <div class="google-btn" onclick="loginGoogle()">
            <img src="logo google.png" class="google-icon">
            <span>Lanjutkan dengan Google</span>
        </div>

        <div class="register">
            Belum punya akun? <a href="../daftar/index.php">Daftar</a>
        </div>
    </div>

</div>
<script>
const nohp = document.getElementById("nohp");
const password = document.getElementById("password");
const toggle = document.querySelector(".toggle");
const tombol = document.getElementById("masukBtn");

function cekInput() {
    if (nohp.value.trim() !== "" && password.value.trim() !== "") {
        tombol.disabled = false;
        tombol.style.backgroundColor = "#4CAF50";
        tombol.style.color = "white";
        tombol.style.cursor = "pointer";
    } else {
        tombol.disabled = true;
        tombol.style.backgroundColor = "#cfcfcf";
        tombol.style.color = "black";
        tombol.style.cursor = "not-allowed";
    }
}

function loginGoogle() {
    google.accounts.id.initialize({
        client_id: "236608514782-qgofkat2qpt5cndchk77pvkbg1i3fhhe.apps.googleusercontent.com",
        callback: handleCredentialResponse
    });

    google.accounts.id.prompt();
}

function handleCredentialResponse(response) {
    console.log("Token:", response.credential);
    alert("Login Google Berhasil!");
}

function togglePassword() {
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}

nohp.addEventListener("input", cekInput);
password.addEventListener("input", cekInput);
document.addEventListener("DOMContentLoaded", function () {

    // Animasi masuk dari kiri
    document.body.classList.add("page-enter");
    setTimeout(() => {
        document.body.classList.add("page-enter-active");
    }, 10);

    // Animasi keluar ke kanan saat klik link
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

});

</script>
</body>
</html>