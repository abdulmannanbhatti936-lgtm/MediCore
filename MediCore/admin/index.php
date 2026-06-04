<?php
session_start();
require_once __DIR__ . '/../config/db.php';
requireLogin(['admin']);
$totalDoctors = (int) $pdo->query('SELECT COUNT(*) FROM doctors')->fetchColumn();
$totalPatients = (int) $pdo->query('SELECT COUNT(*) FROM patients')->fetchColumn();
$todayAppointments = (int) $pdo->query('SELECT COUNT(*) FROM appointments WHERE appointment_date=CURDATE()')->fetchColumn();
$totalRevenue = (float) $pdo->query("SELECT COALESCE(SUM(d.fee),0) FROM appointments a JOIN doctors d ON d.id=a.doctor_id WHERE a.status='completed'")->fetchColumn();
$weekly = $pdo->query("SELECT DATE_FORMAT(appointment_date,'%a') label, COUNT(*) total FROM appointments WHERE appointment_date>=DATE_SUB(CURDATE(),INTERVAL 6 DAY) GROUP BY appointment_date ORDER BY appointment_date")->fetchAll();
$monthly = $pdo->query("SELECT DATE_FORMAT(created_at,'%b') label, COUNT(*) total FROM patients GROUP BY DATE_FORMAT(created_at,'%Y-%m') ORDER BY MIN(created_at)")->fetchAll();
$recent = $pdo->query("SELECT a.id,a.appointment_date,a.appointment_time,a.status,u1.name patient_name,u2.name doctor_name FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users u1 ON u1.id=p.user_id JOIN doctors d ON d.id=a.doctor_id JOIN users u2 ON u2.id=d.user_id ORDER BY a.created_at DESC LIMIT 5")->fetchAll();
$pageTitle = 'Admin Dashboard'; include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><h2 class="fw-bold mb-0">Admin Dashboard</h2><a class="btn btn-primary" href="<?= $baseUrl ?>/admin/appointments.php">Manage Appointments</a></div>
<div class="row g-3 mb-4"><?php foreach ([['Total Doctors',$totalDoctors,'bi-person-badge'],['Total Patients',$totalPatients,'bi-people'],['Today Appointments',$todayAppointments,'bi-calendar-check'],['Total Revenue','PKR '.number_format($totalRevenue,0),'bi-currency-dollar']] as $c): ?><div class="col-md-6 col-xl-3"><div class="card p-3"><div class="d-flex justify-content-between"><div><small class="text-muted"><?= e($c[0]) ?></small><h4 class="fw-bold mb-0 mt-1"><?= e((string)$c[1]) ?></h4></div><div class="stat-icon"><i class="bi <?= e($c[2]) ?>"></i></div></div></div></div><?php endforeach; ?></div>
<div class="row g-3 mb-4"><div class="col-lg-6"><div class="card p-3"><h6>Appointments This Week</h6><canvas id="c1"></canvas></div></div><div class="col-lg-6"><div class="card p-3"><h6>Patients Per Month</h6><canvas id="c2"></canvas></div></div></div>
<div class="table-wrap"><div class="table-responsive"><table class="table table-striped table-hover mb-0"><thead><tr><th>#</th><th>Patient</th><th>Doctor</th><th>Date</th><th>Status</th></tr></thead><tbody><?php foreach($recent as $r): ?><tr><td><?= e((string)$r['id']) ?></td><td><?= e($r['patient_name']) ?></td><td><?= e($r['doctor_name']) ?></td><td><?= e($r['appointment_date']) ?> <?= e(substr($r['appointment_time'],0,5)) ?></td><td><?= e(ucfirst($r['status'])) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
<script>
const w=<?= json_encode($weekly) ?>,m=<?= json_encode($monthly) ?>;
new Chart(document.getElementById('c1'),{type:'line',data:{labels:w.map(x=>x.label),datasets:[{label:'Appointments',data:w.map(x=>x.total),borderColor:'#3b82f6',tension:.35}]}})
new Chart(document.getElementById('c2'),{type:'bar',data:{labels:m.map(x=>x.label),datasets:[{label:'Patients',data:m.map(x=>x.total),backgroundColor:'#3b82f6'}]}})
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
