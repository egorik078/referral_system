<?php
session_start();
require_once(__DIR__ . '/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['user'];

// Referal sonini hisoblash
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE referer = ?");
$stmt->execute([$username]);
$refCount = $stmt->fetchColumn();

// Dinamik bazaviy domenni olish
$baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
$refLink = $baseUrl . "/index.php?ref=" . urlencode($username);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard {
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        a {
            color: #00bcd4;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .logout {
            margin-top: 20px;
            display: inline-block;
            padding: 8px 16px;
            background-color: #f44336;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
<div class="dashboard">
    <h2>Salom, <?= htmlspecialchars($username) ?>!</h2>
    <p><strong>Referal havolangiz:</strong></p>
    <p><code><?= $refLink ?></code></p>
    <p><strong>Siz orqali ro‘yxatdan o‘tganlar soni:</strong> <?= $refCount ?></p>
    <a class="logout" href="logout.php">Chiqish</a>
</div>
</body>
</html>
