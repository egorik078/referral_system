<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, referrals FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$refLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/index.php?ref=$userId";

$topStmt = $conn->query("SELECT username, referrals FROM users ORDER BY referrals DESC LIMIT 10");
$topUsers = $topStmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function copyReferral() {
            navigator.clipboard.writeText("<?= $refLink ?>");
            alert("Referal havola nusxalandi!");
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Salom, <?= htmlspecialchars($user['username']) ?>!</h2>
    <p>Sizning referal havolangiz:</p>
    <code><?= $refLink ?></code>
    <button onclick="copyReferral()">Havolani nusxalash</button>
    <p>Siz orqali kirganlar soni: <?= $user['referrals'] ?></p>

    <hr>
    <h3>TOP 10 foydalanuvchi</h3>
    <ol>
        <?php foreach ($topUsers as $u): ?>
            <li><?= htmlspecialchars($u['username']) ?> — <?= $u['referrals'] ?> ta</li>
        <?php endforeach; ?>
    </ol>
</div>
</body>
</html>
