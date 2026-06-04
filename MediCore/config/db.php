<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'medicore_db';
$username = 'root';
$password = 'Manan.01';
$baseUrl = '/MediCore';

$pdo = null;
$errors = [];

// Try common local XAMPP credential combinations to avoid setup friction.
$passwordCandidates = array_unique([$password, '', 'Manan.01']);
foreach ($passwordCandidates as $candidate) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $candidate, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        break;
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}

if (!$pdo) {
    $message = 'Database connection failed. Please verify MySQL is running, medicore_db exists, and credentials are correct.';
    if (!empty($_GET['debug_db'])) {
        $message .= '<br><small>' . e($errors[0] ?? 'Unknown database error.') . '</small>';
    }
    die($message);
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirectTo(string $path): void
{
    global $baseUrl;
    header('Location: ' . $baseUrl . $path);
    exit;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function requireLogin(array $roles = []): void
{
    if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
        redirectTo('/auth/login.php');
    }
    if (!empty($roles) && !in_array($_SESSION['role'], $roles, true)) {
        setFlash('danger', 'You are not authorized to access this page.');
        redirectTo('/auth/login.php');
    }
}
