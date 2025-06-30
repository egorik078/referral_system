<?php
session_start();
require 'db.php';

// === 1. REFERAL LINK bo‘lsa ===
if (isset($_GET['ref'])) {
    $_SESSION['ref'] = $_GET['ref'];
    header("Location: https://tmstart.me/sevengaming");
    exit;
}

// === 2. FORM YUBORILGAN BO‘LSA ===
$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    if ($username === "") {
        $msg = "Iltimos, foydalanuvchi nomini kiriting!";
    } else {
        // Mavjud foydalanuvchini tekshiramiz
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            // Yangi foydalanuvchini yaratamiz
            $referer = $_SESSION['ref'] ?? null;
            $insert = $conn->prepare("INSERT INTO users (username, referer) VALUES (?, ?)");
            $insert->execute([$username, $referer]);
        }

        // Kirish holatini eslab qolamiz
        $_SESSION['user'] = $username;
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Referal Tizim</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Ismingizni kiriting</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Foydalanuvchi nomi" required><br>
        <button type="submit">Kirish</button>
    </form>
    <p><?= $msg ?></p>
</div>
</body>
</html>
