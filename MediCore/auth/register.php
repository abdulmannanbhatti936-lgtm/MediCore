<?php
session_start();
require_once __DIR__ . '/../config/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['password'] ?? '') !== ($_POST['confirm_password'] ?? '')) $error = 'Passwords do not match.';
    else {
        $exists = $pdo->prepare('SELECT id FROM users WHERE email=? LIMIT 1');
        $exists->execute([trim($_POST['email'] ?? '')]);
        if ($exists->fetch()) $error = 'Email already exists.';
        else {
            $pdo->beginTransaction();
            try {
                $u = $pdo->prepare('INSERT INTO users (name,email,password,role,phone) VALUES (?,?,?,"patient",?)');
                $u->execute([trim($_POST['name']), trim($_POST['email']), password_hash($_POST['password'], PASSWORD_DEFAULT), trim($_POST['phone'])]);
                $pid = (int) $pdo->lastInsertId();
                $p = $pdo->prepare('INSERT INTO patients (user_id,age,gender,blood_group,address,emergency_contact) VALUES (?,NULL,NULL,NULL,"","")');
                $p->execute([$pid]);
                $pdo->commit();
                setFlash('success', 'Registration successful. Please login.');
                redirectTo('/auth/login.php');
            } catch (Throwable $e) { $pdo->rollBack(); $error = 'Registration failed.'; }
        }
    }
}
$pageTitle = 'Register'; $isAuthPage = true; include __DIR__ . '/../includes/header.php';
?>
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-5"><div class="card p-4">
<h3 class="fw-bold text-center mb-3">Patient Registration</h3>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="POST" class="row g-3">
<div class="col-12"><input name="name" class="form-control" placeholder="Full Name" required></div>
<div class="col-12"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
<div class="col-12"><input name="phone" class="form-control" placeholder="Phone" required></div>
<div class="col-md-6"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
<div class="col-md-6"><input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required></div>
<div class="col-12"><button class="btn btn-primary w-100">Create Account</button></div>
</form></div></div></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
