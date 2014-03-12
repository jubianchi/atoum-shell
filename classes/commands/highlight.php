<?php

namespace atoum\shell\commands;

use Hoa\Console\Readline\Autocompleter\Path;
use Hoa\Console\Readline\Readline;
use atoum\shell\command;
use atoum\shell\cli;
use mageekguy\atoum\adapter;
use mageekguy\atoum\iterators\recursives\directory\factory;
use mageekguy\atoum\writers\std\out;

class highlight extends command
{
    protected $cli;
    protected $completer;

    public function __construct(cli\command $command = null, adapter $adapter = null)
    {
        parent::__construct($adapter);

        $this->cli = $command ?: new cli\command();
        $this->completer = new Path(
            Path::PWD,
            function($path) {
                $factory = new factory();

                return $factory
                    ->refuseDots()
                    ->getIterator(realpath($path))
                ;
            }
        );
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

    public function getWordDefinition()
    {
        $completer = new Path();

        return $completer->getWordDefinition();
    }

    public function complete(& $prefix)
    {
        $completer = new Path();

        return $completer->complete($prefix);
    }
}
