<?php

namespace atoum\shell\report\fields\shell\runner;

use
    atoum\shell,
    atoum\shell\report\fields\shell\runner
;

class prompt extends runner
{
    protected $prompt;

    public function __construct(shell\runner $runner, array $events = null, shell\cli\prompt $prompt = null)
    {
        parent::__construct($runner, $events ?: array(shell\runner::prompt));

        $this->setPrompt($prompt);
    }

    public function setPrompt(shell\cli\prompt $prompt = null)
    {
        $this->prompt = $prompt ?: new shell\cli\prompt('> ');

        return $this;
    }

    public function getPrompt()
    {
        return $this->prompt;
    }

    function __toString()
    {
        return (string) $this->prompt;
    }
}
