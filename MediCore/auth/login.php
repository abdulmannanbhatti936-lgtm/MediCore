<?php
session_start();
require_once __DIR__ . '/../config/db.php';
if (isset($_SESSION['user_id'], $_SESSION['role'])) {
    $map = ['admin' => '/admin/index.php', 'doctor' => '/doctor/index.php', 'patient' => '/patient/index.php'];
    redirectTo($map[$_SESSION['role']] ?? '/index.php');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1');
    $stmt->execute([trim($_POST['email'] ?? '')]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        if (!empty($_POST['remember_me'])) setcookie('medicore_remember_email', $user['email'], time() + (86400 * 30), '/');
        $map = ['admin' => '/admin/index.php', 'doctor' => '/doctor/index.php', 'patient' => '/patient/index.php'];
        redirectTo($map[$user['role']] ?? '/index.php');
    } else $error = 'Invalid email or password.';
}
$pageTitle = 'Login'; $isAuthPage = true; include __DIR__ . '/../includes/header.php';
?>
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-6 col-lg-4"><div class="card p-4">
<h3 class="fw-bold text-center mb-2">MediCore Login</h3><p class="text-muted text-center">Single login for admin, doctor and patient</p>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="POST">
<div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($_COOKIE['medicore_remember_email'] ?? '') ?>" required></div>
<div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
<div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember_me" id="remember"><label class="form-check-label" for="remember">Remember me</label></div>
<button class="btn btn-primary w-100">Login</button></form>
<div class="text-center small mt-3"><a href="<?= $baseUrl ?>/auth/register.php">Register as patient</a> | <a href="<?= $baseUrl ?>/index.php">Home</a></div>
</div></div></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
