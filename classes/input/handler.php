<?php

namespace atoum\shell\input;

use Hoa\Console\Readline\Autocompleter\Autocompleter;
use Hoa\Console\Readline\Readline;
use mageekguy\atoum\writers\std\out;

abstract class handler implements Autocompleter
{
    abstract public function supports(Readline $input);
    abstract public function handle(Readline $input, out $output);

    public function complete($prefix)
    {
        return null;
    }
}
