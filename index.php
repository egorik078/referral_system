<?php
session_start();
require_once 'db.php';

if (isset($_GET['ref'])) {
    $refId = intval($_GET['ref']);
    if (!isset($_COOKIE["ref_used_$refId"])) {
        $stmt = $conn->prepare("UPDATE users SET referrals = referrals + 1 WHERE id = ?");
        $stmt->execute([$refId]);
        setcookie("ref_used_$refId", '1', time() + (86400 * 30), "/");
    }
    header("Location: https://tmstart.me/sevengaming");
    exit;
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    if ($username !== "") {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            $stmt = $conn->prepare("INSERT INTO users (username) VALUES (?)");
            $stmt->execute([$username]);
            $userId = $conn->lastInsertId();
        } else {
            $userId = $user['id'];
        }

        $_SESSION['user_id'] = $userId;
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "Iltimos, foydalanuvchi nomini kiriting.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ro‘yxatdan o‘tish</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Ismingizni kiriting:</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="masalan: egorikxd" required>
        <button type="submit">Kirish</button>
    </form>
    <p style="color:red;"><?= $msg ?></p>
</div>
</body>
</html>
