<?php

namespace atoum\shell\input\handlers;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use atoum\shell\killable;
use atoum\shell\commands;
use atoum\shell\input\handler;
use mageekguy\atoum\writers\std\out;

class shell extends handler implements killable
{
    protected $command;

    public function __construct(killable\pool $pool)
    {
        $this->command = new commands\shell();
    }

    public function supports(Readline $input)
    {
        return (bool) preg_match('/^!/', $input->getLine());
    }

    public function handle(Readline $input, out $output)
    {
        $input->setLine(ltrim($input->getLine(), '!'));

        $this->command->run($input, $output);

        return $this;
    }

    public function kill($signal = null)
    {
        return $this->command->kill($signal);
    }
}
