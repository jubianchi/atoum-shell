<?php

namespace atoum\shell\tests\units\commands;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use atoum\shell;
use atoum\shell\commands\help as testedClass;

require_once __DIR__ . '/../../runner.php';

class help extends atoum\test
{
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubclassOf('atoum\shell\command')
        ;
    }

    public function testGetName()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getName())->isEqualTo('help')
        ;
    }

    public function testGetShortcut()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getShortcut())->isEqualTo('h')
        ;
    }

    public function testGetMapping()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getMapping())->isEqualTo('\C-h')
        ;
    }
} 