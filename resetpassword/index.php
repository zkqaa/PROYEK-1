<?php
session_start();

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true || !isset($_SESSION['nohp'])) {
    echo "Akses tidak valid";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="left"></div>

    <div class="right">
        <a href="../otp/index.php" class="back">
            <span>←</span>
            <span>Kembali</span>
        </a>

        <img src="logo.jpeg" class="logo" alt="Logo">

        <h2>Reset Password</h2>
        <p class="subtitle">Masukkan kata sandi baru untuk akun kamu</p>

        <form action="simpan.php" method="POST">
            <label>Password Baru</label>
            <div class="password-box">
                <input type="password" name="password_baru" id="password_baru" placeholder="Masukkan password baru" required>
                <span class="toggle" onclick="togglePassword('password_baru')">👁</span>
            </div>

            <div class="strength-box">
                <div class="strength-bar">
                    <div id="strengthFill"></div>
                </div>
                <p id="strengthText" class="strength-text"></p>
            </div>

            <label>Konfirmasi Password</label>
            <div class="password-box">
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" placeholder="Ulangi password baru" required>
                <span class="toggle" onclick="togglePassword('konfirmasi_password')">👁</span>
            </div>

            <p id="errorPassword" class="error-text">Password tidak sama</p>

            <button type="submit" class="masuk-btn" id="saveBtn" disabled>
                Simpan Password
            </button>
        </form>
    </div>
</div>

<script>
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

const passwordBaru = document.getElementById("password_baru");
const konfirmasiPassword = document.getElementById("konfirmasi_password");
const errorText = document.getElementById("errorPassword");
const saveBtn = document.getElementById("saveBtn");
const strengthFill = document.getElementById("strengthFill");
const strengthText = document.getElementById("strengthText");

function cekKekuatanPassword(password) {
    let score = 0;

    if (password.length >= 6) score++;
    if (password.match(/[A-Z]/)) score++;
    if (password.match(/[a-z]/)) score++;
    if (password.match(/[0-9]/)) score++;
    if (password.match(/[^A-Za-z0-9]/)) score++;

    if (password.length === 0) {
        strengthFill.style.width = "0%";
        strengthText.textContent = "";
        return;
    }

    if (score <= 2) {
        strengthFill.style.width = "33%";
        strengthFill.style.background = "#e53935";
        strengthText.textContent = "Kekuatan sandi: Lemah";
        strengthText.style.color = "#e53935";
    } else if (score <= 4) {
        strengthFill.style.width = "66%";
        strengthFill.style.background = "#fb8c00";
        strengthText.textContent = "Kekuatan sandi: Sedang";
        strengthText.style.color = "#fb8c00";
    } else {
        strengthFill.style.width = "100%";
        strengthFill.style.background = "#43a047";
        strengthText.textContent = "Kekuatan sandi: Kuat";
        strengthText.style.color = "#43a047";
    }
}

function cekInput() {
    const pass1 = passwordBaru.value.trim();
    const pass2 = konfirmasiPassword.value.trim();

    const semuaTerisi = pass1 !== "" && pass2 !== "";
    const passwordSama = pass1 === pass2;

    if (pass2 !== "" && !passwordSama) {
        errorText.style.display = "block";
    } else {
        errorText.style.display = "none";
    }

    if (semuaTerisi && passwordSama) {
        saveBtn.disabled = false;
        saveBtn.classList.add("active");
    } else {
        saveBtn.disabled = true;
        saveBtn.classList.remove("active");
    }
}

function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

passwordBaru.addEventListener("input", function () {
    cekInput();
    cekKekuatanPassword(passwordBaru.value);
});
konfirmasiPassword.addEventListener("input", cekInput);
</script>

</body>
</html>