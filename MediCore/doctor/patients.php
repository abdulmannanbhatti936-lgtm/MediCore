<?php session_start(); require_once __DIR__ . '/../config/db.php'; requireLogin(['doctor']);
$d=$pdo->prepare('SELECT id FROM doctors WHERE user_id=? LIMIT 1'); $d->execute([$_SESSION['user_id']]); $doctorId=(int)$d->fetchColumn();
$s=$pdo->prepare("SELECT DISTINCT p.*,u.name FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users u ON u.id=p.user_id WHERE a.doctor_id=? ORDER BY u.name"); $s->execute([$doctorId]); $rows=$s->fetchAll();
$historyStmt = $pdo->prepare("SELECT appointment_date, appointment_time, status, symptoms, notes FROM appointments WHERE doctor_id=? AND patient_id=? ORDER BY appointment_date DESC, appointment_time DESC");
$pageTitle='Doctor Patients'; include __DIR__ . '/../includes/header.php'; ?>
<h2 class="fw-bold mb-3">My Patients</h2>
<div class="table-wrap"><div class="table-responsive"><table class="table table-striped table-hover mb-0"><thead><tr><th>Name</th><th>Age</th><th>Gender</th><th>Blood Group</th><th>Address</th><th>History</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['name']) ?></td><td><?= e((string)$r['age']) ?></td><td><?= e($r['gender']) ?></td><td><?= e($r['blood_group']) ?></td><td><?= e($r['address']) ?></td><td><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#history<?= e((string)$r['id']) ?>">View History</button></td></tr>
<div class="modal fade" id="history<?= e((string)$r['id']) ?>" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">History: <?= e($r['name']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
<?php $historyStmt->execute([$doctorId, (int)$r['id']]); $historyRows = $historyStmt->fetchAll(); ?>
<?php if ($historyRows): ?>
<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>Date</th><th>Time</th><th>Status</th><th>Symptoms</th><th>Notes</th></tr></thead><tbody><?php foreach($historyRows as $h): ?><tr><td><?= e($h['appointment_date']) ?></td><td><?= e(substr($h['appointment_time'],0,5)) ?></td><td><?= e(ucfirst($h['status'])) ?></td><td><?= e($h['symptoms']) ?></td><td><?= e($h['notes']) ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php else: ?>
<p class="text-muted mb-0">No appointment history found for this patient.</p>
<?php endif; ?>
</div></div></div></div>
<?php endforeach; ?></tbody></table></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
