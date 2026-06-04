<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($pageTitle)) {
    $pageTitle = 'MediCore';
}
if (!isset($isAuthPage)) {
    $isAuthPage = false;
}
if (!isset($isPublicPage)) {
    $isPublicPage = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - MediCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="<?= $isAuthPage ? 'auth-body' : '' ?>">
<?php if (!$isAuthPage && !$isPublicPage): ?>
<div class="app-shell">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="app-main">
        <div class="container-fluid p-4">
<?php elseif (!$isAuthPage): ?>
<div class="container py-4">
<?php endif; ?>
