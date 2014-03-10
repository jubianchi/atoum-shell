<?php

namespace atoum\shell\tests\units\commands;

use Hoa\Console\Cursor;
use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use atoum\shell;
use atoum\shell\commands\clear as testedClass;

require_once __DIR__ . '/../../runner.php';

class clear extends atoum\test
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
                ->string($command->getName())->isEqualTo('clear')
        ;
    }

    public function testGetShortcut()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getShortcut())->isEqualTo('c')
        ;
    }

    public function testGetMapping()
    {
        $this
            ->given($command = new testedClass())
            ->then
                ->string($command->getMapping())->isEqualTo('\C-l')
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
            ->and(
                ob_start(),
                Cursor::clear('↕'),
                $clear = trim(ob_get_clean())
            )
            ->and($command = new testedClass())
            ->then
                ->boolean($command->run($readline, $output))->isTrue()
                ->mock($output)
                    ->call('write')->withArguments($clear)->once()
        ;
    }
} 