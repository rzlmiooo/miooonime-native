<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE username='$username'"
);

$user = mysqli_fetch_assoc($query);

if ($user && ($password == $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header("Location: admin");
    exit;
} else {
    echo "Login gagal";
}