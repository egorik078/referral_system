<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];

// Referal sonini hisoblash
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE referer = ?");
$stmt->execute([$user]);
$ref_count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sahifam</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Salom, <?= htmlspecialchars($user) ?>!</h2>
    <p>Referal havolangiz:</p>
    <code>https://sizningsayt.com/index.php?ref=<?= urlencode($user) ?></code>
    <p>Siz orqali ro‘yxatdan o‘tganlar soni: <strong><?= $ref_count ?></strong></p>
    <a href="logout.php">Chiqish</a>
</div>
</body>
</html>
