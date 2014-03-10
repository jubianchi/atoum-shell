<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use mageekguy\atoum\writers\std\out;

class selftest extends run
{
    public function getName()
    {
        return 'selftest';
    }

    public function run(Readline $input, out $output)
    {
        $input->setLine(\atoum\shell\directory . '/tests/units/classes');

        parent::run($input, $output);
    }
}
