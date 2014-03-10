<?php
if (defined('STDIN') === false)
{
    define('STDIN', fopen('php://stdin', 'r'));
}

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/autoloader.php';
