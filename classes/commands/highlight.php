<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use atoum\shell\cli;
use mageekguy\atoum\adapter;
use mageekguy\atoum\iterators\recursives\directory\factory;
use mageekguy\atoum\writers\std\out;

class highlight extends command
{
    public function __construct(cli\command $command = null, adapter $adapter = null)
    {
        parent::__construct($adapter);

        $this->cli = $command ?: new cli\command();
    }

    public function getName()
    {
        return 'highlight';
    }

    public function getShortcut()
    {
        return 'hl';
    }

    public function run(Readline $input, out $output)
    {
        if (is_file($input->getLine()) === false)
        {
            throw new \invalidArgumentException(sprintf('%s is not a file', $input->getLine()));
        }

        $command = 'highlight --out-format xterm256 --style rdark --syntax php --line-number --out-format=xterm256 ' . escapeshellarg($input->getLine());
        $this->cli
            ->reset()
            ->setBinaryPath($command)
            ->run()
        ;

        while ($this->cli->isRunning());

        ob_start('atoum\shell\cli\pager::less');
        echo $this->cli->getStdout();
        ob_get_clean();

        $error = $this->cli->getStdErr();

        if ($error)
        {
            throw new \runtimeException($error);
        }
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
