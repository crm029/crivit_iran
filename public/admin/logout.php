<?php
declare(strict_types=1);
require dirname(__DIR__,2) . '/app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
csrf_check(); $_SESSION=[]; session_regenerate_id(true); redirect('/admin/login.php');
