<?php
declare(strict_types=1);

$host = '127.0.0.1';
$db = 'rtg_tourism';
$user = 'root';
$pass = ''; // Change this if your local MySQL/MariaDB account has a password.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $exception) {
    error_log('RTG database connection error: ' . $exception->getMessage());
    http_response_code(500);
    exit('The database connection failed. Check the database name and credentials in db.php.');
}
