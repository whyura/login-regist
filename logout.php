<?php
// Memulai sesi
session_start();

// Menghapus semua data sesi
session_unset();
session_destroy();

// Arahkan pengguna kembali ke halaman login
header("Location: login.php");
exit();
