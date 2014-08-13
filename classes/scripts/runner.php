<?php

namespace atoum\shell\scripts;

require_once __DIR__ . '/../../constants.php';

use mageekguy\atoum;
use atoum\shell;

class runner extends atoum\script\configurable
{
    const defaultConfigFile = '.atoum.shell.php';

    protected $runner;

    public function __construct($name, atoum\adapter $adapter = null)
    {
        parent::__construct($name, $adapter);

        $this
            ->setRunner()
        ;
    }

    public function setRunner(shell\runner $runner = null)
    {
        $this->runner = $runner ?: new shell\runner();

        return $this->setArgumentHandlers();
    }

    public function getRunner()
    {
        return $this->runner;
    }

    public function run(array $arguments = array())
    {
        parent::run($arguments);

        $this->runner->run($this);

        return $this;
    }
}
