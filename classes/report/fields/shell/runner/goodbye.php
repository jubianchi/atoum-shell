<?php

namespace atoum\shell\report\fields\shell\runner;

use mageekguy\atoum;
use atoum\shell;
use mageekguy\atoum\report\fields;
use atoum\shell\report\fields\shell\runner;

class goodbye extends runner
{
    public function __construct(shell\runner $runner, array $events = null)
    {
        parent::__construct($runner, $events ?: array(shell\runner::stop, shell\runner::terminate));
    }

    function __toString()
    {
        return PHP_EOL . 'Good bye!' . PHP_EOL;
    }
}
