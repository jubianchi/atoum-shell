<?php

namespace atoum\shell;

use Hoa\Console\Readline\Autocompleter\Autocompleter;
use Hoa\Console\Readline\Readline;
use mageekguy\atoum\adapter;
use mageekguy\atoum\writers\std\out;

abstract class command implements Autocompleter
{
    protected $adapter;

    public function __construct(adapter $adapter = null)
    {
        $this->adapter = $adapter ?: new adapter();
    }

    abstract public function getName();

    public function getShortcut()
    {
        return null;
    }

    public function getMapping()
    {
        return null;
    }

    public function getWordDefinition()
    {
        return '.*';
    }

    public function complete(& $prefix)
    {
        return null;
    }

    abstract public function run(Readline $input, out $output);

    public function runMapping(Readline $input, out $output)
    {
        return $this->run($input, $output);
    }
}
