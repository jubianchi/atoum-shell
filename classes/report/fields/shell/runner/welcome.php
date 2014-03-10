<?php

namespace atoum\shell\report\fields\shell\runner;

use mageekguy\atoum;
use atoum\shell;
use atoum\shell\report\fields\shell\runner;

class welcome extends runner
{
    public function __construct(shell\runner $runner, array $events = null)
    {
        parent::__construct($runner, $events ?: array(shell\runner::start));
    }

    function __toString()
    {
        $keywordColorizer = new atoum\cli\colorizer('1;36', null, $this->getRunner());

        return
            new logo($this->getRunner()) .
            '             Welcome to the ' . $keywordColorizer->colorize('atoum interactive shell') . ' !' . PHP_EOL .
            '        ' . new version($this->getRunner()) .
            '                    Use ' . $keywordColorizer->colorize(':help') . ' to get some help.' . PHP_EOL .
            PHP_EOL
        ;
    }
}
