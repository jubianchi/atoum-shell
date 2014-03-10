<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum\adapter;
use mageekguy\atoum\report\fields\runner\event;
use atoum\shell\command;
use atoum\shell\killable;
use mageekguy\atoum\writers\std\out;
use atoum\shell\report\fields;

class nyancat extends command implements killable
{
    protected $run;

    public function getName()
    {
        return 'nyancat';
    }

    public function run(Readline $input, out $output)
    {
        $nyancat = new event\nyancat();
        $this->run = true;

        while ($this->run)
        {
            $output->write($nyancat);
        }
    }

    public function kill($signal = null)
    {
        $this->run = false;

        return true;
    }
}
