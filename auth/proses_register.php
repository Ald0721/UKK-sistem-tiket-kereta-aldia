<?php
require_once "../config/Database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = (new Database())->connect();
    $user = new User($db);

    $email = $_POST['email'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    if ($user->emailExists($email)) {
        header("Location: register.php?error=Email sudah terdaftar");
        exit;
    }

    if ($user->register($email, $nama, $password)) {
        header("Location: login.php?success=Berhasil daftar, silakan login");
    } else {
        header("Location: register.php?error=Gagal daftar");
    }
}
