<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Autocompleter\Path;
use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use mageekguy\atoum\adapter;
use mageekguy\atoum\iterators\recursives\directory\factory;
use mageekguy\atoum\writers\std\out;

class cd extends command
{
    protected $completer;

    public function __construct(adapter $adapter = null)
    {
        parent::__construct($adapter);

        $this->completer = new Path(
            Path::PWD,
            function($path) {
                $factory = new factory();

                return new \CallbackFilterIterator(
                    $factory->refuseDots()->getIterator(realpath($path)),
                    function($entry) {
                        return $entry->isDir();
                    }
                );
            }
        );
    }

    public function getName()
    {
        return 'cd';
    }

    public function run(Readline $input, out $output)
    {
        if (@$this->adapter->chdir($input->getLine()) === false)
        {
            throw new \invalidArgumentException($input->getLine() . ' is not a directory');
        }

        return true;
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
