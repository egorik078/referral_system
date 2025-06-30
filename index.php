<?php
session_start();
require_once(__DIR__ . '/db.php');

// === 1. REFERAL LINK BILAN KIRGAN FOYDALANUVCHI ===
if (isset($_GET['ref'])) {
    $ref = trim($_GET['ref']);

    // Faqat bir marta ref +1 bo'lishi uchun COOKIE tekshiramiz
    if (!isset($_COOKIE['ref_given_' . $ref])) {
        try {
            // Foydalanuvchi mavjudligini tekshirish
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$ref]);
            $exists = $stmt->fetch();

            if (!$exists) {
                // Yangi foydalanuvchi yaratamiz, o'zi referer bo'ladi
                $insert = $conn->prepare("INSERT INTO users (username, referer) VALUES (?, ?)");
                $insert->execute([$ref, $ref]);
            }

            // COOKIE yozamiz (30 kun saqlanadi)
            setcookie('ref_given_' . $ref, '1', time() + (86400 * 30), "/");

        } catch (PDOException $e) {
            // Xato bo'lsa, xatolikni chiqaramiz
            die("Xatolik: " . $e->getMessage());
        }
    }

    // Har holda kanalga yo'naltiramiz
    header("Location: https://tmstart.me/sevengaming");
    exit;
}

// === 2. FORM YUBORILGAN HOLAT ===
$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    if ($username === "") {
        $msg = "Start username girizmeli.";
    } else {
        try {
            // Foydalanuvchi mavjudligini tekshiramiz
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!$user) {
                $insert = $conn->prepare("INSERT INTO users (username) VALUES (?)");
                $insert->execute([$username]);
            }

            $_SESSION['user'] = $username;
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            $msg = "Xatolik: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SevenGaming bonus</title>
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
        .box {
            background: #1f1f1f;
            padding: 30px;
            border-radius: 12px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: none;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        p {
            margin-top: 10px;
            color: #f44336;
        }
    </style>
</head>
<body>
<div class="box">
    <h2>Start username</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Masalan: egorikxd" required>
        <button type="submit">Ulgama girmek</button>
    </form>
    <?php if ($msg): ?>
        <p><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
</div>
</body>
</html>
