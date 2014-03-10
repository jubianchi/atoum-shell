<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use atoum\shell\killable;
use mageekguy\atoum\writers\std\out;

class shell extends command implements killable
{
    protected $cli;

    public function __construct(adapter $adapter = null)
    {
        parent::__construct($adapter);

        $this->cli = new \atoum\shell\cli\command();
    }

    public function getName()
    {
        return 'shell';
    }

    public function getShortcut()
    {
        return 'sh';
    }

    public function kill($signal = null)
    {
        return $this->cli->kill($signal);
    }

    public function run(Readline $input, out $output)
    {
        $this->cli->setBinaryPath($input->getLine());

        $this->cli->run();

        while ($this->cli->isRunning())
        {
            $output->write($this->cli->getIncrementalStdout());
        }

        $output->write($this->cli->getStdout());

        $error = $this->cli->getStdErr();

        if ($error)
        {
            throw new \runtimeException($error);
        }
    }
}
