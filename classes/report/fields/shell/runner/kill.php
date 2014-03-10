<?php

namespace atoum\shell\report\fields\shell\runner;

use mageekguy\atoum;
use atoum\shell;
use mageekguy\atoum\report\fields;
use atoum\shell\report\fields\shell\runner;

class kill extends runner
{
    public function __construct(shell\runner $runner, array $events = null)
    {
        parent::__construct($runner, $events ?: array(shell\runner::kill));
    }

    function __toString()
    {
        $colorizer = new atoum\cli\colorizer(30, 47);

        return PHP_EOL . $colorizer->colorize('^C') . PHP_EOL;
    }
}
