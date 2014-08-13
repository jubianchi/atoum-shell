<?php

namespace atoum\shell\input\handlers;

use atoum\shell\killable;
use Hoa\Console\Readline\Readline;
use atoum\shell;
use atoum\shell\input\handler;
use mageekguy\atoum\writers\std\out;
use mageekguy\atoum\iterators\recursives\directory\factory;
use mageekguy\atoum;

class source extends handler
{
    protected $script;
    protected $readline;
    protected $output;
    protected $completer;

    public function __construct(shell\scripts\runner $script, Readline $readline = null, out $output = null)
    {
        $this->script = $script;
        $this->readline = $readline ?: new Readline();
        $this->output = $output ?: new out();
        $this->completer = new \Hoa\Console\Readline\Autocompleter\Path(
            null,
            function($path) {
                $factory = new factory();

                return $factory
                    ->refuseDots()
                    ->getIterator(realpath($path))
                ;
            }
        );
    }

    public function supports(Readline $input)
    {
        return (bool) preg_match('/^(?:source |\.)/', $input->getLine());
    }

    public function handle(Readline $input, out $output)
    {
        $line = trim(preg_replace('/^(?:source |\.)/', '', $input->getLine()));

        ob_start();

        set_error_handler(
            function($no, $msg) {
                ob_end_clean();

                throw new \badMethodCallException($msg);
            }
        );

        $script = $this->script;

        @include $line;

        restore_error_handler();

        $out = ob_get_clean();

        $colorizer = new atoum\cli\colorizer(30, 47);

        if ($out != '' && rtrim($out) == $out)
        {
            $out = $out . $colorizer->colorize('%') . PHP_EOL;
        }

        $output->write($out);
    }

    public function getWordDefinition()
    {
        return $this->completer->getWordDefinition();
    }

    public function complete(& $prefix)
    {
        return $this->completer->complete($prefix);
    }
}
