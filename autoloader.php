<?php

namespace atoum\shell;

use mageekguy\atoum;

$vendor = __DIR__ . DIRECTORY_SEPARATOR . 'vendor';
$atoum = $vendor . DIRECTORY_SEPARATOR . 'atoum' . DIRECTORY_SEPARATOR . 'atoum';
$hoa = $vendor . DIRECTORY_SEPARATOR . 'hoa';

require_once __DIR__ . '/classes/autoloader.php';
require_once $hoa . '/core/Hoa/Core/Core.php';

return autoloader::get()
    ->addDirectory('mageekguy\atoum', implode(DIRECTORY_SEPARATOR, array($atoum, 'classes')))
    ->addDirectory('Hoa\Core', implode(DIRECTORY_SEPARATOR, array($hoa, 'core', 'Hoa', 'Core')))
    ->addDirectory('Hoa\Console', implode(DIRECTORY_SEPARATOR, array($hoa, 'console', 'Hoa', 'Console')))
    ->addDirectory('Hoa\Stream', implode(DIRECTORY_SEPARATOR, array($hoa, 'stream', 'Hoa', 'Stream')))
    ->addDirectory('Hoa\String', implode(DIRECTORY_SEPARATOR, array($hoa, 'string', 'Hoa', 'String')))
;
