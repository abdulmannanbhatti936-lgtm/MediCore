<?php session_start(); require_once __DIR__ . '/../config/db.php'; requireLogin(['doctor']);
$d=$pdo->prepare('SELECT id FROM doctors WHERE user_id=? LIMIT 1'); $d->execute([$_SESSION['user_id']]); $doctorId=(int)$d->fetchColumn();
$s=$pdo->prepare('SELECT COUNT(*) FROM appointments WHERE doctor_id=? AND appointment_date=CURDATE()'); $s->execute([$doctorId]); $today=(int)$s->fetchColumn();
$s=$pdo->prepare("SELECT COUNT(DISTINCT patient_id) FROM appointments WHERE doctor_id=? AND status='completed'"); $s->execute([$doctorId]); $seen=(int)$s->fetchColumn();
$s=$pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id=? AND status='pending'"); $s->execute([$doctorId]); $pending=(int)$s->fetchColumn();
$s=$pdo->prepare("SELECT a.*,u.name patient_name FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users u ON u.id=p.user_id WHERE a.doctor_id=? ORDER BY a.created_at DESC LIMIT 6"); $s->execute([$doctorId]); $rows=$s->fetchAll();
$pageTitle='Doctor Dashboard'; include __DIR__ . '/../includes/header.php'; ?>
<h2 class="fw-bold mb-3">Doctor Dashboard</h2>
<div class="row g-3 mb-3"><?php foreach([["Today's Appointments",$today],['Total Patients Seen',$seen],['Pending Appointments',$pending]] as $c): ?><div class="col-md-4"><div class="card p-3"><small class="text-muted"><?= e($c[0]) ?></small><h3 class="fw-bold mb-0"><?= e((string)$c[1]) ?></h3></div></div><?php endforeach; ?></div>
<div class="table-wrap"><div class="table-responsive"><table class="table table-striped table-hover mb-0"><thead><tr><th>Patient</th><th>Date</th><th>Status</th><th>Symptoms</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['patient_name']) ?></td><td><?= e($r['appointment_date']) ?> <?= e(substr($r['appointment_time'],0,5)) ?></td><td><?= e(ucfirst($r['status'])) ?></td><td><?= e($r['symptoms']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
