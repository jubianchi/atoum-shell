<?php

namespace atoum\shell\report\fields\cli;

use mageekguy\atoum;
use atoum\shell\report\fields\cli;

class help extends cli
{
    function __toString()
    {
        $keywordColorizer = new atoum\cli\colorizer('1;36', null, $this->getCli());
        $boldColorizer = new atoum\cli\colorizer('1;37', null, $this->getCli());
        $argColorizer = new atoum\cli\colorizer('34', null, $this->getCli());

        return
            $boldColorizer->colorize('How to use:') . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize('$assert') . ' to run assertions' . PHP_EOL .
            PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize(':editor (:e) or ^I') . ' to pop your text editor' . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize(':run (:r)') . ' ' . $argColorizer->colorize('<file or directory>') . ' to run tests' . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize(':version (:v)') . ' to show atoum version information' . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize(':cd') . ' to change directory' . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize(':pwd') . ' to show current directory' . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize(':clear (:c) ^L') . ' to clear the screen' . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize(':help (:h) ^H') . ' to display this help' . PHP_EOL .
            PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize('^C') . ' to kill currently running process' . PHP_EOL .
            ' - Use ' . $keywordColorizer->colorize('^D') . ' to exit the shell' . PHP_EOL .
            ' - Use your usual key bindings ' . $keywordColorizer->colorize('^A, ^E, ^W, ...') . ' to get a better experience' . PHP_EOL .
            PHP_EOL
        ;
    }
}
