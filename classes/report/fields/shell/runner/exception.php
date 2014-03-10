<?php

namespace atoum\shell\report\fields\shell\runner;

use mageekguy\atoum;
use atoum\shell;
use mageekguy\atoum\report\fields;
use atoum\shell\report\fields\shell\runner;

class exception extends runner
{
    public function __construct(shell\runner $runner, array $events = null)
    {
        parent::__construct($runner, $events ?: array(shell\runner::exception));
    }

    function __toString()
    {
        $errorColorizer = new atoum\cli\colorizer(37, 41);
        $exception = $this->getRunner()->getLastException();

        $messages = explode(PHP_EOL, $exception->getMessage());
        array_unshift($messages, PHP_EOL);
        array_unshift($messages, '[' . get_class($exception) . ']');
        array_unshift($messages, PHP_EOL);

        $maxlength = 0;
        foreach ($messages as $message)
        {
            $length = strlen($message);
            $maxlength = $maxlength < $length ? $length : $maxlength;
        }

        $messages[] = PHP_EOL;

        foreach ($messages as & $message)
        {
            $message = $errorColorizer->colorize(sprintf(' %-' . $maxlength . 's ', trim($message)));
        }

        return
            PHP_EOL .
            rtrim(implode(PHP_EOL, $messages), PHP_EOL) .
            PHP_EOL . PHP_EOL
        ;
    }
}
