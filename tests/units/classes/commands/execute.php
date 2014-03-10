<?php

namespace atoum\shell\tests\units\commands;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use atoum\shell;
use atoum\shell\commands\execute as testedClass;

require_once __DIR__ . '/../../runner.php';

class execute extends atoum\test
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
                ->string($command->getName())->isEqualTo('execute')
        ;
    }

    public function testGetShortcut()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getShortcut())->isEqualTo('x')
        ;
    }

    public function testRun()
    {
        $this
            ->given($adapter = new atoum\test\adapter())
            ->and($adapter->exec->doesNothing())
            ->and($adapter->tempnam = $buffer = uniqid())
            ->and(
                $this->mockGenerator->shuntParentClassCalls(),
                $output = new \mock\mageekguy\atoum\writers\std\out()
            )
            ->and($readline = new Readline())
            ->and($command = new testedClass(null, $adapter))
            ->if($adapter->file_get_contents->doesNothing())
            ->then
                ->boolean($command->run($readline, $output))->isTrue()
        ;
    }
} 