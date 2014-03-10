<?php

namespace atoum\shell\input;

use Hoa\Console\Readline\Autocompleter\Autocompleter;
use Hoa\Console\Readline\Readline;

class completer implements Autocompleter
{
    protected $readline;
    protected $handlers = array();

    public function __construct(Readline $readline, array $handlers = array())
    {
        $this->readline = $readline;

        foreach ($handlers as $handler)
        {
            $this->addHandler($handler);
        }
    }

    public function addHandler(Autocompleter $handler)
    {
        $this->handlers[] = $handler;

        return $this;
    }

    public function complete($prefix)
    {
        foreach ($this->handlers as $handler)
        {
            if ($handler->supports($this->readline))
            {
                return $handler->complete($prefix);
            }
        }

        return null;
    }
}
