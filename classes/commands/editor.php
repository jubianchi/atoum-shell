<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use mageekguy\atoum\adapter;
use atoum\shell\killable\pool;
use mageekguy\atoum\writers\std\out;
use atoum\shell\runner;

class editor extends execute
{
    protected static $buffer;

    public function getName()
    {
        return 'editor';
    }

    public function getShortcut()
    {
        return 'e';
    }

    public function run(Readline $input, out $output)
    {
        if (preg_match('/^!$/', $input->getLine()) || isset(static::$buffer) === false)
        {
            if (isset(static::$buffer) === true && $this->adapter->is_file(static::$buffer))
            {
                $this->adapter->unlink(static::$buffer);
            }

            static::$buffer = $this->adapter->tempnam(sys_get_temp_dir(), uniqid());
        }

        $this->adapter->exec('$EDITOR ' . static::$buffer . ' > `tty` < `tty`');

        $code = $this->adapter->file_get_contents(static::$buffer);

        if (trim($code) !== '')
        {
            $input->setLine($code);

            return parent::run($input, $output);
        }

        return false;
    }
}
