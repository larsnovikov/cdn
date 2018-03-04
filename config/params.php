<?php

if (file_exists(__DIR__ . '/cdn-local.php')) {
    $cdn = array_merge(require __DIR__ . '/cdn.php', require __DIR__ . '/cdn-local.php');
} else {
    $cdn = [];
}
return [
    'cdn' => $cdn,
    'adminEmail' => 'admin@example.com',
];
