<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use mageekguy\atoum\writers\std\out;

class pwd extends command
{
    public function getName()
    {
        return 'pwd';
    }

    public function run(Readline $input, out $output)
    {
        if (($pwd = $this->adapter->getcwd()) === false)
        {
            throw new \runtimeException('Could not get process working directory');
        }

        $output->write($pwd . PHP_EOL);

        return true;
    }
}
