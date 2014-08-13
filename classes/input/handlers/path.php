<?php

namespace atoum\shell\input\handlers;

use mageekguy\atoum;
use atoum\shell\killable;
use atoum\shell\commands;
use Hoa\Console\Readline\Readline;
use mageekguy\atoum\iterators\recursives\directory\factory;
use atoum\shell\input\handler;
use mageekguy\atoum\writers\std\out;

class path extends handler implements killable
{
    protected $readline;
    protected $command;

    public function __construct(Readline $readline, commands\run $command = null)
    {
        $this->readline = $readline;
        $this->command = $command ?: new commands\run();
        $this->completer = new \Hoa\Console\Readline\Autocompleter\Path(
            null,
            function($path) {
                $factory = new factory();

                return $factory
                    ->refuseDots()
                    ->getIterator(realpath($path))
                ;
            }
        );
    }

    public function supports(Readline $input)
    {
        return $input->getLine() != '' && sizeof(glob($input->getLine() . '*')) > 0;
    }

    public function handle(Readline $input, out $output)
    {
        $command = new commands\run();
        $command->run($input, $output);

        return $this;
    }

    public function kill($signal = null)
    {
        return $this->command->kill($signal);
    }

    public function getWordDefinition()
    {
        return $this->completer->getWordDefinition();
    }

    public function complete(& $prefix)
    {
        return $this->completer->complete($prefix);
    }
}
