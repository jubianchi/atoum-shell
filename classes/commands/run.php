<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Autocompleter\Path;
use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use atoum\shell\killable;
use mageekguy\atoum\iterators\recursives\directory\factory;
use mageekguy\atoum\writers\std\out;
use atoum\shell\executor;

class run extends command implements killable
{
    protected $executor;
    protected $output;

    public function __construct(executor\test $executor = null)
    {
        $this->executor = $executor ?: new executor\test();
        $this->completer = new Path(
            Path::PWD,
            function($path) {
                $factory = new factory();

                return $factory
                    ->refuseDots()
                    ->getIterator(realpath($path))
                ;
            }
        );
    }

    public function getName()
    {
        return 'run';
    }

    public function getShortcut()
    {
        return 'r';
    }

    public function run(Readline $input, out $output)
    {
        if (preg_match('/^(?P<path>.*?)(?: (?P<binary>.*))?$/', $input->getLine(), $matches) > 0)
        {
            $this->executor->run($matches['path'], $output, isset($matches['binary']) ? $matches['binary'] : null);
        }
    }

    public function kill($signal = null)
    {
        return $this->executor->kill($signal);
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
