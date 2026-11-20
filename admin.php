<?php
declare(strict_types=1);
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_admin();

$notice = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = (string)($_POST['action'] ?? '');
    try {
        if ($action === 'save_service') {
            $id = filter_var($_POST['service_id'] ?? '', FILTER_VALIDATE_INT);
            $name = trim((string)($_POST['service_name'] ?? ''));
            $category = trim((string)($_POST['category'] ?? ''));
            $location = trim((string)($_POST['location'] ?? ''));
            $description = trim((string)($_POST['description'] ?? ''));
            $image = trim((string)($_POST['image_path'] ?? ''));
            if ($name === '' || mb_strlen($name) > 150 || mb_strlen($category) > 80 ||
                mb_strlen($location) > 150 || mb_strlen($image) > 500 ||
                ($image !== '' && !filter_var($image, FILTER_VALIDATE_URL))) {
                throw new RuntimeException('Enter a service name and valid details. Image must be a full URL or left blank.');
            }
            if ($id) {
                $stmt = $pdo->prepare('UPDATE services SET service_name=?, category=?, location=?, description=?, image_path=? WHERE service_id=?');
                $stmt->execute([$name, $category, $location, $description, $image, $id]);
                $notice = 'Service updated.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO services(service_name,category,location,description,image_path,active) VALUES(?,?,?,?,?,1)');
                $stmt->execute([$name, $category, $location, $description, $image]);
                $notice = 'Service added.';
            }
        } elseif ($action === 'toggle_service') {
            $id = filter_var($_POST['service_id'] ?? '', FILTER_VALIDATE_INT);
            if (!$id) throw new RuntimeException('Invalid service.');
            $stmt = $pdo->prepare('UPDATE services SET active = IF(active=1,0,1) WHERE service_id=?');
            $stmt->execute([$id]);
            $notice = 'Service visibility updated.';
        } else {
            throw new RuntimeException('Unknown action.');
        }
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}

$editService = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM services WHERE service_id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $editService = $stmt->fetch() ?: null;
}
$services = $pdo->query('SELECT * FROM services ORDER BY service_name')->fetchAll();
$bookings = $pdo->query('SELECT b.*, s.service_name FROM bookings b JOIN services s ON s.service_id=b.service_id ORDER BY b.created_at DESC')->fetchAll();
$token = csrf_token();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>RTG Admin Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body><header class="site-header"><a class="brand" href="index.php">RTG <span>TOURISM</span></a><nav><a href="index.php">View website</a><form method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?= e($token) ?>"><button class="link-button">Sign out</button></form></nav></header>
<main class="admin-main"><p class="eyebrow">ADMINISTRATION</p><h2>Dashboard</h2>
<?php if ($notice): ?><p class="success"><?= e($notice) ?></p><?php endif; ?><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
<section class="admin-section"><h3>Booking requests</h3>
<?php if (!$bookings): ?><p>No booking requests have been submitted yet.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Reference</th><th>Guest / contact</th><th>Experience</th><th>Dates</th><th>Guests</th><th>Status</th><th>Update</th></tr></thead><tbody>
<?php foreach ($bookings as $booking): ?><tr>
<td><?= e($booking['booking_reference']) ?></td><td><?= e($booking['contact_details']) ?></td><td><?= e($booking['service_name']) ?></td>
<td><?= e($booking['check_in']) ?> – <?= e($booking['check_out']) ?></td><td><?= (int)$booking['guest_count'] ?></td><td><?= e($booking['status']) ?></td>
<td><form method="post" action="status.php" class="inline-form"><input type="hidden" name="csrf_token" value="<?= e($token) ?>"><input type="hidden" name="id" value="<?= (int)$booking['booking_id'] ?>"><select name="status"><?php foreach (['Pending','Confirmed','Declined','Cancelled'] as $status): ?><option value="<?= e($status) ?>" <?= $booking['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select><button class="small-btn">Save</button></form></td>
</tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>

<section class="admin-section"><h3>Manage sample experiences</h3>
<div class="table-wrap"><table><thead><tr><th>Experience</th><th>Location</th><th>Visibility</th><th>Action</th></tr></thead><tbody>
<?php foreach ($services as $service): ?><tr><td><?= e($service['service_name']) ?></td><td><?= e($service['location']) ?></td><td><?= $service['active'] ? 'Active' : 'Hidden' ?></td><td><a class="small-btn" href="admin.php?edit=<?= (int)$service['service_id'] ?>">Edit</a><form method="post" class="inline-form"><input type="hidden" name="csrf_token" value="<?= e($token) ?>"><input type="hidden" name="action" value="toggle_service"><input type="hidden" name="service_id" value="<?= (int)$service['service_id'] ?>"><button class="small-btn"><?= $service['active'] ? 'Deactivate' : 'Activate' ?></button></form></td></tr><?php endforeach; ?>
</tbody></table></div>
<h3><?= $editService ? 'Edit experience' : 'Add experience' ?></h3>
<form method="post" class="panel service-form"><input type="hidden" name="csrf_token" value="<?= e($token) ?>"><input type="hidden" name="action" value="save_service"><input type="hidden" name="service_id" value="<?= (int)($editService['service_id'] ?? 0) ?>">
<label>Service name<input name="service_name" maxlength="150" value="<?= e($editService['service_name'] ?? '') ?>" required></label>
<div class="pair"><label>Category<input name="category" maxlength="80" value="<?= e($editService['category'] ?? '') ?>"></label><label>Location<input name="location" maxlength="150" value="<?= e($editService['location'] ?? '') ?>"></label></div>
<label>Description<textarea name="description" rows="3"><?= e($editService['description'] ?? '') ?></textarea></label>
<label>Image URL<input name="image_path" type="url" maxlength="500" value="<?= e($editService['image_path'] ?? '') ?>"></label>
<button class="btn">Save experience</button><?php if ($editService): ?><a href="admin.php">Cancel editing</a><?php endif; ?></form>
</section></main></body></html>
