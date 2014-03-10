<?php

namespace atoum\shell\executor;

use mageekguy\atoum;
use atoum\shell;
use atoum\shell\php;

class test implements shell\killable
{
    protected $php;

    public function __construct(php $php = null)
    {
        $this->php = $php ?: new php();
    }

    public function kill($signal = null)
    {
        return $this->php->kill($signal);
    }

    public function run($test, atoum\writers\std\out $output, $binary = null)
    {
        $this->php = new php();
        $binary = $binary ?: implode(DIRECTORY_SEPARATOR, array(shell\directory, 'vendor', 'atoum', 'atoum', 'bin', 'atoum'));
        $this->php
            ->setBinaryPath($binary)
            ->addOption(is_file($test) ? '-f' : '-d', $test)
            ->addOption('-ft')
            ->run()
        ;

        while ($this->php->isRunning())
        {
            $out = $this->php->getIncrementalStdout();

            if (sizeof(trim($out)))
            {
                $output->write($out);
            }
        }

        $output->write($this->php->getStdout());

        $error = $this->php->getStdErr();
        $this->php->reset();

        if ($error)
        {
            throw new \runtimeException($error);
        }
    }
}
