<?php
session_start();

if (!isset($_SESSION['otp']) || !isset($_SESSION['nohp'])) {
    echo "Akses tidak valid";
    exit();
}

if (!isset($_SESSION['otp_last_sent'])) {
    $_SESSION['otp_last_sent'] = time();
}

$remaining = 30 - (time() - $_SESSION['otp_last_sent']);
if ($remaining < 0) {
    $remaining = 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
    <a href="../lupakatasandi/index.php" class="back">
        <span class="arrow">←</span>
        <span class="text">Kembali</span>
    </a>

    <img src="logo.jpeg" class="logo">

    <h2>Verifikasi Kode OTP</h2>
    <p class="subtitle">
        Masukkan OTP yang dikirim ke <?= htmlspecialchars($_SESSION['nohp']) ?>
    </p>

    <form action="verifikasi.php" method="POST">
        <div class="otp-container">
            <input type="text" maxlength="1" class="otp" name="o1" required>
            <input type="text" maxlength="1" class="otp" name="o2" required>
            <input type="text" maxlength="1" class="otp" name="o3" required>
            <input type="text" maxlength="1" class="otp" name="o4" required>
        </div>

        <div class="resend-wrapper">
            <a href="resend_otp.php" class="resend disabled" id="resendLink">
                Kirim Ulang OTP (<?= $remaining ?> detik)
            </a>
        </div>

        <button type="submit" id="nextBtn" disabled>Selanjutnya</button>
    </form>
</div>

<script>
document.body.classList.add("page-enter");
setTimeout(() => {
    document.body.classList.add("page-enter-active");
}, 10);

document.querySelectorAll("a.back").forEach(link => {
    link.addEventListener("click", function (e) {
        e.preventDefault();
        const href = this.getAttribute("href");

        document.body.classList.add("page-exit");

        setTimeout(() => {
            window.location.href = href;
        }, 400);
    });
});

const inputs = document.querySelectorAll(".otp");
const button = document.getElementById("nextBtn");

inputs.forEach((input, index) => {
    input.addEventListener("input", (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');

        if (input.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }

        checkOTP();
    });

    input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !input.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

function checkOTP() {
    let filled = true;

    inputs.forEach(input => {
        if (input.value === "") {
            filled = false;
        }
    });

    if (filled) {
        button.disabled = false;
        button.classList.add("active");
    } else {
        button.disabled = true;
        button.classList.remove("active");
    }
}

const resendLink = document.getElementById("resendLink");
let timeLeft = <?= $remaining ?>;

// fungsi format ke 00:21
function formatTime(seconds) {
    let minutes = Math.floor(seconds / 60);
    let secs = seconds % 60;

    minutes = String(minutes).padStart(2, '0');
    secs = String(secs).padStart(2, '0');

    return `${minutes}:${secs}`;
}

function updateCountdown() {
    if (timeLeft > 0) {
        resendLink.innerHTML = `Kirim Ulang OTP (${formatTime(timeLeft)})`;
        resendLink.style.color = "#b0b0b0";
        resendLink.style.pointerEvents = "none";
        resendLink.style.cursor = "not-allowed";
        timeLeft--;
    } else {
        resendLink.innerHTML = "Kirim Ulang OTP";
        resendLink.style.color = "#2e7d32";
        resendLink.style.pointerEvents = "auto";
        resendLink.style.cursor = "pointer";
        resendLink.style.fontWeight = "500";
    }
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>

</body>
</html>