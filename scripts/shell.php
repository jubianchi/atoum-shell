<?php

namespace mageekguy\atoum\shell;

use mageekguy\atoum\shell\scripts;

require_once __DIR__ . '/../autoloader.php';

$runner = new scripts\runner($_SERVER['argv'][0]);
$runner->run();
