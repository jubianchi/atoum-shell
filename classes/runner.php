<?php

namespace atoum\shell;

use Hoa\Console\Readline\Autocompleter\Aggregate;
use mageekguy\atoum;
use atoum\shell;
use Hoa\Console\Readline\Readline;

class runner extends atoum\cli implements killable, atoum\observable
{
    const start = 'runnerStart';
    const stop = 'runnerStop';
    const prompt = 'runnerPrompt';
    const exception = 'runnerException';
    const kill = 'runnerKill';
    const terminate = 'runnerTerminate';

    protected $output;
    protected $report;
    protected $observers = null;
    protected $lastException;

    protected $readline;
    protected $pool;

    public function __construct(atoum\adapter $adapter = null)
    {
        parent::__construct($adapter);

        $this->observers = new \splObjectStorage();

        $this->setReport();
        $this->readline = new Readline();
        $this->pool = $pool = new shell\killable\pool();
        $this->output = $output = new atoum\writers\std\out();

        $runner = $this;

        $this->readline->addMapping('\C-d', function() use ($runner) { $runner->terminate(); });

        if (function_exists('pcntl_signal'))
        {
            declare(ticks = 1);
            pcntl_signal(SIGINT, function() use ($runner, $output, $pool) {
                $pool->kill(SIGINT);

                $output->write($this->callObservers(self::kill)->report);
            });
            pcntl_signal(SIGTERM, function() use ($runner) { $runner->terminate(); });
        }
    }

    public function setReport($report = null)
    {
        if ($this->report !== null)
        {
            $this->removeObserver($this->report);
        }

        $this->report = $report ?: new shell\reports\realtime\shell($this);

        return $this->addObserver($this->report);
    }

    public function getReport()
    {
        return $this->report;
    }

    public function kill($signal = null)
    {
        $this->output->write($this->callObservers(self::kill)->report);

        exit((int) $signal ?: 1);
    }

    public function terminate($signal = null)
    {
        $this->pool->remove($this);

        while ($this->pool->kill($signal ?: SIGTERM));

        $this->output->write($this->callObservers(self::terminate)->report);

        exit(0);
    }

    public function read()
    {
        return $this->readline->readLine($this->callObservers(self::prompt)->report);
    }

    public function addObserver(atoum\observer $observer)
    {
        $this->observers->attach($observer);

        return $this;
    }

    public function removeObserver(atoum\observer $observer)
    {
        $this->observers->detach($observer);

        return $this;
    }

    public function callObservers($event)
    {
        foreach ($this->observers as $observer)
        {
            $observer->handleEvent($event, $this);
        }

        return $this;
    }

    public function getScore()
    {
        // TODO: Implement getScore() method.
    }

    public function getBootstrapFile()
    {
        // TODO: Implement getBootstrapFile() method.
    }

    public function run(shell\scripts\runner $runner)
    {
        $this->pool->add($this);

        $this->output->write($this->callObservers(self::start)->report);

        $handlers = array(
            new shell\input\handlers\command(
                 array(
                    new shell\commands\help(),
                    new shell\commands\version(),
                    new shell\commands\clear(),
                    $run = new shell\commands\run(),
                    new shell\commands\editor(),
                    new shell\commands\nyancat(),
                    new shell\commands\cd(),
                    new shell\commands\pwd(),
                    new shell\commands\execute(),
                    new shell\commands\shell(),
                    new shell\commands\highlight(),
                    new shell\commands\selftest()
                ),
                $this->readline,
                $this->output
            ),
            new shell\input\handlers\source($runner, $this->readline, $this->output),
            new shell\input\handlers\shell($this->pool, $this->readline, $this->output),
            new shell\input\handlers\path($this->readline, $run),
            new shell\input\handlers\code()
        );

        $this->readline->setAutocompleter(new Aggregate($handlers));

        while (true)
        {
            trim($this->read());

            try
            {
                foreach ($handlers as $handler)
                {
                    if ($handler->supports($this->readline))
                    {
                        if ($handler instanceof killable)
                        {
                            $this->pool
                                ->add($handler)
                                ->remove($handler->handle($this->readline, $this->output))
                            ;
                        }
                        else
                        {
                            $handler->handle($this->readline, $this->output);
                        }

                        break;
                    }
                }
            }
            catch(\exception $exception)
            {
                $this->lastException = $exception;

                $this->output->write($this->callObservers(self::exception)->report);
            }
        }
    }

    public function getLastException()
    {
        return $this->lastException;
    }
}
