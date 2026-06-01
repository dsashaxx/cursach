<?php
session_start();
require_once 'logger.php';
$login = $_SESSION['user'] ?? 'unknown';
writeLog($login, 'LOGOUT');
session_unset();
session_destroy();
header('Location: login.php');
exit;
