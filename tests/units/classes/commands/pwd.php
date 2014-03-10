<?php

namespace atoum\shell\tests\units\commands;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use atoum\shell;
use atoum\shell\commands\pwd as testedClass;

require_once __DIR__ . '/../../runner.php';

class pwd extends atoum\test
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
                ->string($command->getName())->isEqualTo('pwd')
        ;
    }

    public function testRun()
    {
        $this
            ->given($adapter = new atoum\test\adapter())
            ->and(
                $this->mockGenerator->shuntParentClassCalls(),
                $output = new \mock\mageekguy\atoum\writers\std\out()
            )
            ->and($readline = new Readline())
            ->and($command = new testedClass($adapter))
            ->if($adapter->getcwd = $path = uniqid())
            ->then
                ->boolean($command->run($readline, $output))->isTrue()
                ->mock($output)
                    ->call('write')->withArguments($path . PHP_EOL)->once()
            ->if($adapter->getcwd = false)
            ->then
                ->exception(function() use ($command, $output, $readline) {
                        $command->run($readline, $output);
                    }
                )
                    ->isInstanceOf('runtimeException')
                    ->hasMessage('Could not get process working directory')
        ;
    }
} 