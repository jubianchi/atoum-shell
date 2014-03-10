<?php

namespace atoum\shell\tests\units;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use atoum\shell;

require_once __DIR__ . '/../runner.php';

class command extends atoum\test
{
    public function testClass()
    {
        $this
            ->testedClass
                ->isAbstract()
                ->hasMethod('getName')
                ->hasMethod('run')
        ;
    }

    public function testGetShortcut()
    {
        $this
            ->if(
                $this->mockGenerator->orphanize('__construct'),
                $command = new \mock\atoum\shell\command()
            )
            ->then
                ->variable($command->getShortcut())->isNull()
        ;
    }

    public function testGetMapping()
    {
        $this
            ->if(
                $this->mockGenerator->orphanize('__construct'),
                $command = new \mock\atoum\shell\command()
            )
            ->then
                ->variable($command->getMapping())->isNull()
        ;
    }

    public function testComplete()
    {
        $this
            ->if(
                $this->mockGenerator->orphanize('__construct'),
                $command = new \mock\atoum\shell\command()
            )
            ->then
                ->variable($command->complete(uniqid()))->isNull()
        ;
    }

    public function testRunMapping()
    {
        $this
            ->given($out = new atoum\writers\std\out())
            ->and($readline = new Readline())
            ->if(
                $this->mockGenerator->orphanize('__construct'),
                $command = new \mock\atoum\shell\command()
            )
            ->and($this->calling($command)->run = true)
            ->then
                ->boolean($command->runMapping($readline, $out))->isTrue()
                ->mock($command)
                    ->call('run')->withArguments($readline, $out)->once()
        ;
    }
} 