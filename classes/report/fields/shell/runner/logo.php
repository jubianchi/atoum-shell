<?php

namespace atoum\shell\report\fields\shell\runner;

use mageekguy\atoum;
use mageekguy\atoum\report\fields;
use atoum\shell\report\fields\shell\runner;

class logo extends runner
{
    function __toString()
    {
        if ($this->getRunner()->isTerminal())
        {
            return (string) new fields\runner\atoum\logo();
        }

        return '';
    }
}
