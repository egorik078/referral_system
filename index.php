<?php
session_start();
require_once(__DIR__ . '/db.php'); // Fayl mavjudligiga 100% kafolat

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
        try {
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
        } catch (PDOException $e) {
            $msg = "Bazaga ulanishda xatolik: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Referal Tizim</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            width: 300px;
        }
        input, button {
            padding: 10px;
            margin-top: 10px;
            width: 100%;
            border-radius: 8px;
            border: none;
        }
        button {
            background-color: #00bcd4;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0097a7;
        }
        p {
            margin-top: 10px;
            color: #f44336;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Ismingizni kiriting</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Foydalanuvchi nomi" required><br>
        <button type="submit">Girmek</button>
    </form>
    <?php if ($msg): ?>
        <p><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
</div>
</body>
</html>
