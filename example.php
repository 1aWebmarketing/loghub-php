<?php
require __DIR__ . '/vendor/autoload.php';

use LogHub\LogHub;

LogHub::init('API_KEY');

$logId = LogHub::log(
    'group-1',
    'my actual log content, string, array, class instance or json'
);
