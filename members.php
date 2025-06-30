<?php
session_start();
require_once 'db.php';

$adminPassword = 'e12e12e1234re3';

// === Parolni tekshirish ===
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $password = $_POST['password'] ?? '';
        if ($password === $adminPassword) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: members.php");
            exit;
        } else {
            $error = "Nadogry parol!";
        }
    }

    // Parol so‘rov shakli
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Agzalar Girmek</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="container">
        <h2>Admin paroly girizin</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="Parol" required>
            <button type="submit">Girmek</button>
        </form>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// === Kirgandan keyin foydalanuvchilar ro'yxati ===
$stmt = $conn->query("SELECT username, referrals, created_at FROM users ORDER BY referrals DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agzalar bolumi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Referal peydalanyjylar</h2>
    <table border="1" width="100%" cellpadding="8" style="background-color: #1f1f1f; color: white; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Peydalanyjy</th>
                <th>Referallar sany</th>
                <th>Registrasiya edilen wagty</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= $user['referrals'] ?></td>
                    <td><?= $user['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
