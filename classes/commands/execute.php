<?php

namespace atoum\shell\commands;

use atoum\shell\killable;
use atoum\shell\php;
use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use mageekguy\atoum\adapter;
use atoum\shell\command;
use mageekguy\atoum\writers\std\out;
use atoum\shell\executor;

class execute extends command implements killable
{
    protected $executor;

    public function __construct(executor\code $executor = null, adapter $adapter = null)
    {
        parent::__construct($adapter);

        $this->executor =  new executor\code();
    }

    public function getName()
    {
        return 'execute';
    }

    public function getShortcut()
    {
        return 'x';
    }

    public function run(Readline $input, out $output)
    {
        $this->executor->run($input->getLine(), $output);

        return true;
    }

    public function kill($signal = null)
    {
        return $this->executor->kill($signal);
    }
}
