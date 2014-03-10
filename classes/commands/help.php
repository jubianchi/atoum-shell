<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use mageekguy\atoum\writers\std\out;
use atoum\shell\report\fields;

class help extends command
{
    public function getName()
    {
        return 'help';
    }

    public function getShortcut()
    {
        return 'h';
    }

    public function getMapping()
    {
        return '\C-h';
    }

    public function run(Readline $input, out $output)
    {
        $output->write((string) new fields\cli\help());
    }
}
