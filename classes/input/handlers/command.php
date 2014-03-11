<?php

namespace atoum\shell\input\handlers;

use atoum\shell\killable;
use Hoa\Console\Readline\Autocompleter\Word;
use Hoa\Console\Readline\Readline;
use atoum\shell;
use atoum\shell\input\handler;
use mageekguy\atoum\writers\std\out;

class command extends handler implements killable
{
    protected $readline;
    protected $output;
    protected $commands = array();
    protected $command;

    public function __construct(array $commands = array(), Readline $readline = null, out $output = null)
    {
        $this->readline = $readline ?: new Readline();
        $this->output = $output ?: new out();

        foreach ($commands as $command)
        {
            $this->addCommand($command);
        }
    }

    public function addCommand(shell\command $command)
    {
        $this->commands[] = $command;

        if (($mapping = $command->getMapping()) !== null)
        {
            $output = $this->output;
            $readline = $this->readline;

            $this->readline->addMapping(
                $mapping,
                function() use ($command, $output, $readline) {
                    $command->runMapping($readline, $output);
                }
            );
        }

        return $this;
    }

    public function supports(Readline $input)
    {
        return (bool) preg_match('/^:/', $input->getLine());
    }

    public function handle(Readline $input, out $output)
    {
        $line = $input->getLine();

        foreach ($this->commands as $command)
        {
            $identifier = preg_quote($command->getName(), '/');
            if (($shortcut = $command->getShortcut()) !== null)
            {
                $identifier .= '|' . preg_quote($shortcut, '/');
            }

            if (preg_match('/^:(?:' . $identifier . ')(?:\s+|$)(?P<input>.*)$/', $line, $matches))
            {
                $input->setLine($matches['input']);

                $this->command = $command;

                $this->command->run(
                    $this->readline,
                    $this->output
                );

                $this->command = null;

                return $this;
            }
        }

        throw new \badMethodCallException(sprintf('Unknown command %s', $input));
    }

    public function kill($signal = null)
    {
        if (null !== $this->command && $this->command instanceof killable)
        {
            $this->command->kill($signal);
        }
    }

    public function getWordDefinition()
    {
        $line = $this->readline->getLine();

        if (preg_match('/^:(?P<name>\w*?)$/', $line, $matches) > 0)
        {
            return '^\:\w*$';
        }

        if (preg_match('/^:(?P<name>.*?)\s+(?P<args>.*)$/', $line, $matches) > 0)
        {
            foreach ($this->commands as $command)
            {
                if ($command->getName() === $matches['name'] || $command->getShortcut() === $matches['name'])
                {
                    return $command->getWordDefinition();
                }
            }
        }

        return parent::getWordDefinition();
    }

    public function complete(& $prefix)
    {
        $words = array();
        $line = $this->readline->getLine();

        if (preg_match('/^:(?P<name>.*?)\s+(?P<args>.*)$/', $line, $matches) > 0)
        {
            foreach ($this->commands as $command)
            {
                if ($command->getName() === $matches['name'] || $command->getShortcut() === $matches['name'])
                {
                    return $command->complete($matches['args']);
                }
            }
        }

        foreach ($this->commands as $command)
        {
            $words[] = ':' . $command->getName();
        }

        $autocomplete = new Word($words);

        return $autocomplete->complete($prefix);
    }
}
