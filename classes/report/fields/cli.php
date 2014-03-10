<?php

namespace atoum\shell\report\fields;

use mageekguy\atoum;
use mageekguy\atoum\report\field;

abstract class cli extends field
{
    protected $cli;

    public function __construct(array $events = null, atoum\cli $cli = null)
    {
        parent::__construct($events);

        $this->setCli($cli);
    }

    public function setCli(atoum\cli $cli = null)
    {
        $this->cli = $cli ?: new atoum\cli();

        return $this;
    }

    public function getCli()
    {
        return $this->cli;
    }
}
