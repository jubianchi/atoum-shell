<?php

namespace atoum\shell\report\fields\shell\runner;

use mageekguy\atoum;
use atoum\shell;
use atoum\shell\report\fields\shell\runner;

class version extends runner
{
    function __toString()
    {
        return 'atoum shell version ' . shell\version . ' by ' . shell\author . PHP_EOL;
    }
}
