<?php

namespace atoum\shell\tests\units\commands;

use mageekguy\atoum;
use atoum\shell;
use atoum\shell\commands\cd as testedClass;

require_once __DIR__ . '/../../runner.php';

class cd extends atoum\test
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
                ->string($command->getName())->isEqualTo('cd')
        ;
    }

    public function testRun()
    {
        $this
            ->given($adapter = new atoum\test\adapter())
            ->and($output = new atoum\writers\std\out())
            ->and($readline = new \mock\Hoa\Console\Readline\Readline())
            ->and($command = new testedClass($adapter))
            ->if($path = uniqid())
            ->and($adapter->chdir = true)
            ->and($this->calling($readline)->getLine = $path)
            ->then
                ->boolean($command->run($readline, $output))->isTrue()
            ->if($adapter->chdir = false)
            ->then
                ->exception(function() use ($command, $path, $output, $readline) {
                        $command->run($readline, $output);
                    }
                )
                    ->isInstanceOf('invalidArgumentException')
                    ->hasMessage(sprintf('%s is not a directory', $path))
        ;
    }
} 