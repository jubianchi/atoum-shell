<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use mageekguy\atoum\iterators\recursives\directory\factory;
use mageekguy\atoum\writers\std\out;

class cd extends command
{
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

    public function complete($prefix)
    {
        $separator = strrpos($prefix, DIRECTORY_SEPARATOR);
        $separator = $separator === false ? null : $separator + 1;

        if ($separator !== null)
        {
            $dir = substr($prefix, 0, $separator);
            $prefix = substr($prefix, $separator) ?: '';
        }
        else
        {
            $dir = dirname($prefix);
        }

        $factory = new factory();
        $iterator = $factory
            ->refuseDots()
            ->getIterator($dir)
        ;

        $directories = null;
        foreach ($iterator as $entry) if ($entry->isDir() === true)
        {
            $basename = $entry->getBasename();

            if ($prefix === '' || strpos($basename, $prefix) === 0)
            {
                $directories[] = $basename;
            }
        }

        return sizeof($directories) === 1 ? current($directories) : $directories;
    }
}
