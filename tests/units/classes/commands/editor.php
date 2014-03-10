<?php

namespace atoum\shell\tests\units\commands;

use mageekguy\atoum;
use atoum\shell;
use atoum\shell\commands\editor as testedClass;

require_once __DIR__ . '/../../runner.php';

class editor extends atoum\test
{
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubclassOf('atoum\shell\commands\execute')
        ;
    }

    public function testGetName()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getName())->isEqualTo('editor')
        ;
    }

    public function testGetShortcut()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getShortcut())->isEqualTo('e')
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
            ->and($readline = new \mock\Hoa\Console\Readline\Readline())
            ->and($command = new testedClass(null, $adapter))
            ->if($adapter->file_get_contents->doesNothing())
            ->then
                ->boolean($command->run($readline, $output))->isFalse()
                ->adapter($adapter)
                    ->call('exec')->withArguments('$EDITOR ' . $buffer  . ' > `tty` < `tty`')->once()
            ->if($adapter->file_get_contents = 'echo phpversion() . PHP_EOL;')
            ->then
                ->boolean($command->run($readline, $output))->isTrue()
                ->mock($output)
                    ->call('write')->withArguments(phpversion() . PHP_EOL)->once()
            ->if($adapter->is_file = true)
            ->and($adapter->unlink->doesNothing())
            ->and($this->calling($readline)->getLine = '!')
            ->then
                ->boolean($command->run($readline, $output))->isTrue()
                ->adapter($adapter)
                    ->call('unlink')->withArguments($buffer)->once()
        ;
    }
} 