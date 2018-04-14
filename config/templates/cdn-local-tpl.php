<?php

// add your frontend ips here
$frontendIps = [
    '127.0.0.1'
];

// configure AMQP connection
$cropQueue = [
    'port' => 5672,
    'user' => 'public',
    'password' => 'public',
    'queueName' => 'cdn.crop.queue',
    'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
    'dsn' => 'amqp://public:public@172.17.0.1:5672/%2F',
];

return [
    'frontends' => $frontendIps,
    'cropQueue' => $cropQueue
];