<?php

namespace atoum\shell\commands;

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
        foreach ($iterator as $entry)
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
