<?php

$cdn = array_merge(require __DIR__ . '/cdn.php', require __DIR__ . '/cdn-local.php');
return [
    'cdn' => $cdn,
    'adminEmail' => 'admin@example.com',
];
