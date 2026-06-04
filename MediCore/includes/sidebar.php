<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPath = $_SERVER['PHP_SELF'] ?? '';
$role = $_SESSION['role'] ?? '';
$menu = [
    'admin' => [
        ['/admin/index.php', 'Dashboard', 'bi-speedometer2'],
        ['/admin/doctors.php', 'Doctors', 'bi-person-badge'],
        ['/admin/patients.php', 'Patients', 'bi-people'],
        ['/admin/appointments.php', 'Appointments', 'bi-calendar2-check'],
        ['/admin/reports.php', 'Reports', 'bi-bar-chart-line'],
    ],
    'doctor' => [
        ['/doctor/index.php', 'Dashboard', 'bi-speedometer2'],
        ['/doctor/appointments.php', 'Appointments', 'bi-calendar2-week'],
        ['/doctor/patients.php', 'Patients', 'bi-person-lines-fill'],
        ['/doctor/prescriptions.php', 'Prescriptions', 'bi-capsule-pill'],
    ],
    'patient' => [
        ['/patient/index.php', 'Dashboard', 'bi-speedometer2'],
        ['/patient/book-appointment.php', 'Book Appointment', 'bi-calendar-plus'],
        ['/patient/history.php', 'History', 'bi-clock-history'],
    ],
];
?>
<aside class="sidebar">
    <div>
        <h4 class="fw-bold text-white mb-1">MediCore</h4>
        <small class="text-light-emphasis">Healthcare Suite</small>
    </div>
    <nav class="mt-4">
        <?php foreach ($menu[$role] ?? [] as [$url, $label, $icon]): ?>
            <a class="sidebar-link <?= str_contains($currentPath, trim($url, '/')) ? 'active' : '' ?>" href="<?= $baseUrl . $url ?>">
                <i class="bi <?= e($icon) ?>"></i>
                <span><?= e($label) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="mt-auto pt-3 border-top border-secondary-subtle">
        <div class="text-white small mb-2"><?= e($_SESSION['name'] ?? 'User') ?></div>
        <a class="btn btn-sm btn-outline-light w-100" href="<?= $baseUrl ?>/auth/logout.php">Logout</a>
    </div>
</aside>
