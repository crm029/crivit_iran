<?php
declare(strict_types=1);

$root = dirname(__DIR__);
if (is_file($root . '/.env')) {
    foreach (file($root . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim(trim($value), "\"'");
    }
}
function envv(string $key, ?string $default = null): ?string {
    return $_ENV[$key] ?? $default;
}
$debug = envv('APP_DEBUG', 'false') === 'true';
ini_set('display_errors', $debug ? '1' : '0');
error_reporting(E_ALL);
session_name(envv('SESSION_NAME', 'crivit_session'));
session_set_cookie_params(['httponly'=>true, 'secure'=>!empty($_SERVER['HTTPS']), 'samesite'=>'Lax']);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/helpers.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        envv('DB_HOST','127.0.0.1'), envv('DB_PORT','3306'), envv('DB_DATABASE','crivit_iran'));
    $pdo = new PDO($dsn, envv('DB_USERNAME','root'), envv('DB_PASSWORD',''), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
