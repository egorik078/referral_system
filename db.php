<?php
$host = "db.be-mons1.bengt.wasmernet.com";
$dbname = "ref_table";
$username = "27d6a8807cc680007d9c8eeb04c9";
$password = "068627d6-a880-7e7d-8000-7a764a721464";

try {
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Bazaga ulanishda xatolik: " . $e->getMessage());
}
?>
