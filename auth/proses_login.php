<?php
session_start();
require_once "../config/Database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = (new Database())->connect();
    $user = new User($db);

    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $_SESSION['user'] = [
            'id' => $loggedInUser['id'],
            'nama' => $loggedInUser['nama'],
            'email' => $loggedInUser['email'],
            'role' => $loggedInUser['role']
        ];
        header("Location: ../index.php");
    } else {
        header("Location: login.php?error=Email atau password salah");
    }
}
