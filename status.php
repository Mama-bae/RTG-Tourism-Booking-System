<?php
declare(strict_types=1);
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}
verify_csrf();
$allowed = ['Pending', 'Confirmed', 'Declined', 'Cancelled'];
$status = (string)($_POST['status'] ?? '');
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id && in_array($status, $allowed, true)) {
    $stmt = $pdo->prepare('UPDATE bookings SET status=? WHERE booking_id=?');
    $stmt->execute([$status, $id]);
}
header('Location: admin.php');
exit;
