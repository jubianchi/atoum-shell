<?php

namespace atoum\shell\input\handlers;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use atoum\shell\killable;
use atoum\shell\executor;
use atoum\shell\input\handler;
use mageekguy\atoum\writers\std\out;

class code extends handler implements killable
{
    protected $runner;

    public function __construct(executor\code $executor = null)
    {
        $this->executor = $executor ?: new executor\code();
    }

    public function supports(Readline $input)
    {
        return $input->getLine() != '';
    }

    public function handle(Readline $input, out $output = null)
    {
        $this->executor->run($input->getLine(), $output);

        return $this;
    }

    public function kill($signal = null)
    {
        return $this->executor->kill($signal);
    }
}
