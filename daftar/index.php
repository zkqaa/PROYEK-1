<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>

<div class="container">
    <div class="left"></div>

    <div class="right">
        <img src="logo.jpeg" class="logo">
        <h2>Daftar</h2>

        <form action="daftar.php" method="POST">
            <label>No. HP</label>
            <input type="text" name="nohp" id="nohp" placeholder="Masukkan nomor HP" required>

            <label>Kata Sandi</label>
            <div class="password-box">
                <input type="password" name="password" id="password" placeholder="Masukkan kata sandi" required>
                <span class="toggle" onclick="togglePassword()">👁</span>
            </div>

            <label>Konfirmasi Kata Sandi</label>
            <div class="password-box">
                <input type="password" name="konfirmasi" id="konfirmasi" placeholder="Ulangi kata sandi" required>
                <span class="toggle" onclick="togglePasswordConfirm()">👁</span>
            </div>

            <p id="errorPassword" class="error-text" style="display:none;">Kata sandi tidak sama</p>

            <button class="masuk-btn" id="masukBtn" type="submit">
                Daftar
            </button>
        </form>

        <div class="divider">Atau</div>

        <div class="google-btn" onclick="loginGoogle()">
            <img src="logo google.png" class="google-icon">
            <span>Daftar dengan Google</span>
        </div>
    </div>
</div>

<script>
const nohp = document.getElementById("nohp");
const password = document.getElementById("password");
const konfirmasi = document.getElementById("konfirmasi");
const errorText = document.getElementById("errorPassword");
const tombol = document.getElementById("masukBtn");

function cekInput() {
    const semuaTerisi =
        nohp.value.trim() !== "" &&
        password.value.trim() !== "" &&
        konfirmasi.value.trim() !== "";

    const passwordSama = password.value === konfirmasi.value;

    if (konfirmasi.value !== "" && !passwordSama) {
        errorText.style.display = "block";
    } else {
        errorText.style.display = "none";
    }

    if (semuaTerisi && passwordSama) {
        tombol.disabled = false;
        tombol.classList.add("active");
    } else {
        tombol.disabled = true;
        tombol.classList.remove("active");
    }
}

nohp.addEventListener("input", cekInput);
password.addEventListener("input", cekInput);
konfirmasi.addEventListener("input", cekInput);

function togglePassword() {
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}

function togglePasswordConfirm() {
    const input = document.getElementById("konfirmasi");

    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}

function loginGoogle() {
    google.accounts.id.initialize({
        client_id: "236608514782-98nnuoegt2644e2n1i8maioc1stca105.apps.googleusercontent.com",
        callback: handleCredentialResponse
    });

    google.accounts.id.prompt();
}

function handleCredentialResponse(response) {
    console.log("Token:", response.credential);
    alert("Login Google Berhasil!");
}

cekInput();
</script>
</body>
</html>