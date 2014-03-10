<?php

namespace atoum\shell\commands;

use Hoa\Console\Cursor;
use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use mageekguy\atoum\writers\std\out;

class clear extends command
{
    public function getName()
    {
        return 'clear';
    }

    public function getShortcut()
    {
        return 'c';
    }

    public function getMapping()
    {
        return '\C-l';
    }

    public function run(Readline $input, out $output)
    {
        ob_start();
        Cursor::clear('↕');

        $output->write(trim(ob_get_clean()));

        return true;
    }
}
