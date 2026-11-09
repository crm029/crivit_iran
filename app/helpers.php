<?php
declare(strict_types=1);

function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $path): never { header('Location: ' . $path); exit; }
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['_csrf'];
}
function csrf_check(): void {
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419); exit('درخواست معتبر نیست. صفحه را تازه‌سازی کنید.');
    }
}
function admin_user(): ?array { return $_SESSION['admin'] ?? null; }
function require_admin(): void { if (!admin_user()) redirect('/admin/login.php'); }
function slugify(string $value): string {
    $value = trim(mb_strtolower($value, 'UTF-8'));
    $value = preg_replace('/[^\p{L}\p{N}]+/u', '-', $value) ?? '';
    return trim($value, '-') ?: bin2hex(random_bytes(5));
}
function setting(string $key, string $default = ''): string {
    $q = db()->prepare('SELECT setting_value FROM site_settings WHERE setting_key = ?');
    $q->execute([$key]);
    $value = $q->fetchColumn();
    return $value === false ? $default : (string)$value;
}
