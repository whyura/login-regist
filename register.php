<?php
// Termasuk file konfigurasi database
require_once "config/db.php";

// Variabel untuk menyimpan pesan error
$error = "";

// Proses registrasi saat formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input pengguna
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom harus diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password tidak sesuai.";
    } else {
        // Enkripsi password menggunakan password_hash
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Periksa apakah email sudah terdaftar
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Masukkan data pengguna ke dalam database
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $password_hashed);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Terjadi kesalahan, coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="public/css/registration-style.css">
</head>
<body>

<div class="container">
    <div class="form-card">
        <h3>Registrasi Pengguna</h3>
        <?php if ($error): ?>
            <div class="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="input-box">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-box">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-box">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-box">
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p class="link-text">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</div>

</body>
</html>

