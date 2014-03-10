<?php

namespace atoum\shell\commands;

use mageekguy\atoum;
use atoum\shell;
use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use mageekguy\atoum\writers\std\out;

class version extends command
{
    public function getName()
    {
        return 'version';
    }

    public function getShortcut()
    {
        return 'v';
    }

    public function run(Readline $input, out $output)
    {
        $output->write('atoum shell version ' . shell\version . ' by ' . shell\author . PHP_EOL);
    }
}
