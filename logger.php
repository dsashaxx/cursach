<?php

function writeLog($login, $action) {
    $dir = __DIR__ . '/logs';
    if (!is_dir($dir)) {
        mkdir($dir);
    }
    $time = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $line = "$time | ip:$ip | login:$login | action:$action" . PHP_EOL;

    file_put_contents($dir . '/auth.log', $line, FILE_APPEND);
}
