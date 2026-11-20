<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

function fail_request(string $message, int $code = 422): void {
    http_response_code($code);
    echo json_encode(['message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail_request('Method not allowed.', 405);
}
verify_csrf();

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    fail_request('The submitted form could not be read.');
}

$serviceId = filter_var($data['service_id'] ?? null, FILTER_VALIDATE_INT);
$guestCount = filter_var($data['guest_count'] ?? null, FILTER_VALIDATE_INT);
$checkIn = (string)($data['check_in'] ?? '');
$checkOut = (string)($data['check_out'] ?? '');
$fullName = trim((string)($data['full_name'] ?? ''));
$contact = trim((string)($data['contact_details'] ?? ''));

$dateIn = DateTime::createFromFormat('!Y-m-d', $checkIn);
$dateOut = DateTime::createFromFormat('!Y-m-d', $checkOut);
$today = new DateTime('today');

if (!$serviceId || !$guestCount || $guestCount < 1 || $guestCount > 20 ||
    $fullName === '' || mb_strlen($fullName) > 120 ||
    $contact === '' || mb_strlen($contact) > 180 ||
    !$dateIn || $dateIn->format('Y-m-d') !== $checkIn ||
    !$dateOut || $dateOut->format('Y-m-d') !== $checkOut ||
    $dateIn < $today || $dateOut <= $dateIn) {
    fail_request('Please check all fields. Dates must be valid, check-out must be after check-in, and guests must be between 1 and 20.');
}

$service = $pdo->prepare('SELECT service_id FROM services WHERE service_id = ? AND active = 1');
$service->execute([$serviceId]);
if (!$service->fetch()) {
    fail_request('Please select an available experience.');
}

$reference = 'RTG-' . strtoupper(bin2hex(random_bytes(4)));
$stmt = $pdo->prepare(
    "INSERT INTO bookings (service_id, check_in, check_out, guest_count, contact_details, booking_reference, status)
     VALUES (?, ?, ?, ?, ?, ?, 'Pending')"
);
$stmt->execute([$serviceId, $checkIn, $checkOut, $guestCount, $fullName . ' | ' . $contact, $reference]);

echo json_encode([
    'message' => 'Your booking request was received. Reference: ' . $reference . '. Status: Pending. This is not a confirmed reservation.',
    'reference' => $reference
], JSON_UNESCAPED_UNICODE);
