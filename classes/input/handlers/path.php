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
    protected $command;

    public function __construct(commands\run $command = null)
    {
        $this->command = $command ?: new commands\run();
    }

    public function supports(Readline $input)
    {
        return sizeof(glob($input->getLine() . '*')) > 0;
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

    public function complete($prefix)
    {
        $separator = strrpos($this->readline->getLine(), DIRECTORY_SEPARATOR);
        $separator = $separator === false ? null : $separator + 1;

        if ($separator !== null)
        {
            $dir = substr($this->readline->getLine(), 0, $separator);
            $prefix = substr($this->readline->getLine(), $separator) ?: '';
        }
        else
        {
            $dir = dirname($this->readline->getLine());
        }

        if (is_dir($dir) === false)
        {
            $dir = dirname($dir);
        }

        $factory = new factory();
        $iterator = $factory
            ->refuseDots()
            ->getIterator(realpath($dir))
        ;

        $directories = null;
        foreach ($iterator as $entry)
        {
            $basename = $entry->getBasename();

            if ($prefix === '' || strpos($basename, $prefix) === 0)
            {
                $directories[] = ($separator === strlen($this->readline->getLine()) ? '/' : '') . $basename;
            }
        }

        return sizeof($directories) === 1 ? current($directories) : $directories;
    }
}
