<?php
declare(strict_types=1);
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare("SELECT user_id, password_hash FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['user_id'];
        header('Location: admin.php');
        exit;
    }
    $error = 'Invalid administrator email or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Administrator login</title><link rel="stylesheet" href="style.css"></head>
<body><main class="narrow"><a href="index.php">← Back to website</a><h2>Administrator login</h2>
<form method="post" class="panel">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <label>Email<input name="email" type="email" maxlength="190" autocomplete="username" required></label>
  <label>Password<input name="password" type="password" autocomplete="current-password" required></label>
  <button class="btn" type="submit">Sign in</button>
  <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
</form></main></body></html>
