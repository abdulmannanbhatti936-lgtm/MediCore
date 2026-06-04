<?php
session_start();
require_once __DIR__ . '/config/db.php';
$pageTitle = 'Home'; $isPublicPage = true; include __DIR__ . '/includes/header.php';
?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded-3 mb-4 px-3">
    <a class="navbar-brand fw-bold" href="<?= $baseUrl ?>/index.php">MediCore</a>
    <div class="ms-auto d-flex gap-2">
        <a class="btn btn-outline-primary btn-sm" href="<?= $baseUrl ?>/directory/index.php">Directory</a>
        <a class="btn btn-primary btn-sm" href="<?= $baseUrl ?>/auth/login.php">Login / Register</a>
    </div>
</nav>
<section class="hero mb-4 text-center">
    <h1 class="display-5 fw-bold">MediCore - Your Complete Healthcare Solution</h1>
    <p class="lead opacity-75">Hospital operations, appointment booking, and patient management in one platform.</p>
    <a class="btn btn-light btn-lg" href="<?= $baseUrl ?>/auth/login.php">Get Started</a>
</section>
<section class="row g-3 mb-4">
    <div class="col-md-4"><div class="card p-4 h-100"><i class="bi bi-hospital fs-3 text-primary"></i><h5 class="mt-3">Manage</h5><p class="text-muted mb-0">Doctors, patients, appointments and reports in one panel.</p></div></div>
    <div class="col-md-4"><div class="card p-4 h-100"><i class="bi bi-calendar-check fs-3 text-primary"></i><h5 class="mt-3">Book</h5><p class="text-muted mb-0">Patients book appointments quickly by specialization and date.</p></div></div>
    <div class="col-md-4"><div class="card p-4 h-100"><i class="bi bi-geo-alt fs-3 text-primary"></i><h5 class="mt-3">Directory</h5><p class="text-muted mb-0">Public doctor listing by city and specialty.</p></div></div>
</section>
<section class="card p-4 mb-4"><div class="row text-center g-3">
    <div class="col-md-4"><h2 class="fw-bold text-primary">500+</h2><p class="mb-0">Doctors</p></div>
    <div class="col-md-4"><h2 class="fw-bold text-primary">10,000+</h2><p class="mb-0">Patients</p></div>
    <div class="col-md-4"><h2 class="fw-bold text-primary">50+</h2><p class="mb-0">Cities</p></div>
</div></section>
<footer class="text-center text-muted pb-4">&copy; <?= date('Y') ?> MediCore</footer>
<?php include __DIR__ . '/includes/footer.php'; ?>
