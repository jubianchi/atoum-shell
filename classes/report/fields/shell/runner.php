<?php

namespace atoum\shell\report\fields\shell;

use mageekguy\atoum;
use atoum\shell;
use mageekguy\atoum\report\field;

abstract class runner extends field
{
    protected $runner;

    public function __construct(shell\runner $runner, array $events = null)
    {
        parent::__construct($events);

        $this->setRunner($runner);
    }

    public function setRunner(shell\runner $runner = null)
    {
        $this->runner = $runner ?: new shell\runner();

        return $this;
    }

    public function getRunner()
    {
        return $this->runner;
    }
}
