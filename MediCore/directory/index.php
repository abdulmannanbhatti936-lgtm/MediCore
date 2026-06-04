<?php session_start(); require_once __DIR__ . '/../config/db.php';
$city=trim($_GET['city']??''); $spec=trim($_GET['specialization']??''); $sql='SELECT * FROM directory_listings WHERE 1=1'; $p=[]; if($city!==''){ $sql.=' AND city LIKE ?'; $p[]="%$city%";} if($spec!==''){ $sql.=' AND specialization LIKE ?'; $p[]="%$spec%";} $sql.=' ORDER BY doctor_name'; $s=$pdo->prepare($sql); $s->execute($p); $rows=$s->fetchAll();
$cities = $pdo->query('SELECT DISTINCT city FROM directory_listings WHERE city IS NOT NULL AND city <> "" ORDER BY city')->fetchAll(PDO::FETCH_COLUMN);
$specializations = $pdo->query('SELECT DISTINCT specialization FROM directory_listings WHERE specialization IS NOT NULL AND specialization <> "" ORDER BY specialization')->fetchAll(PDO::FETCH_COLUMN);
$pageTitle='Directory'; $isPublicPage=true; include __DIR__ . '/../includes/header.php'; ?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded-3 mb-4 px-3"><a class="navbar-brand fw-bold" href="<?= $baseUrl ?>/index.php">MediCore</a><div class="ms-auto"><a href="<?= $baseUrl ?>/auth/login.php" class="btn btn-primary btn-sm">Login</a></div></nav>
<h2 class="fw-bold mb-3">Public Doctor Directory</h2>
<form class="row g-2 mb-4">
    <div class="col-md-3">
        <select class="form-select" name="city">
            <option value="">All Cities</option>
            <?php foreach($cities as $cityOption): ?>
                <option value="<?= e($cityOption) ?>" <?= $city === $cityOption ? 'selected' : '' ?>><?= e($cityOption) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select" name="specialization">
            <option value="">All Specializations</option>
            <?php foreach($specializations as $specOption): ?>
                <option value="<?= e($specOption) ?>" <?= $spec === $specOption ? 'selected' : '' ?>><?= e($specOption) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Search</button></div>
</form>
<div class="row g-3"><?php foreach($rows as $r): ?><div class="col-md-6 col-lg-4"><div class="card p-3 h-100"><h5 class="mb-1"><?= e($r['doctor_name']) ?></h5><p class="text-primary mb-1"><?= e($r['specialization']) ?></p><div class="small text-muted mb-2"><?= e($r['clinic_name']) ?> - <?= e($r['city']) ?></div><div class="small mb-1"><i class="bi bi-geo-alt"></i> <?= e($r['address']) ?></div><div class="small mb-3"><i class="bi bi-telephone"></i> <?= e($r['phone']) ?></div><a class="btn btn-sm btn-primary mt-auto" href="<?= $baseUrl ?>/auth/login.php">Book Appointment</a></div></div><?php endforeach; ?></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
