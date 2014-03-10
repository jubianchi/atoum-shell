<?php

namespace atoum\shell\executor;

use mageekguy\atoum;
use atoum\shell;
use atoum\shell\php;
use atoum\shell\report;

class code implements shell\killable
{
    protected $php;
    protected $runner;

    public function __construct(php $php = null, atoum\runner $runner = null)
    {
        $this->php = $php ?: new php();
        $this->runner = $runner ?: new atoum\runner();
    }

    public function kill($signal = null)
    {
        if ($this->php !== null)
        {
            return $this->php->kill($signal);
        }

        return false;
    }

    public function run($code, atoum\writers\std\out $output)
    {
        $input = $this->getCode($code);
        $this->php->run($input);

        while ($this->php->isRunning());

        $out = $this->php->getStdout();

        if (($score = @unserialize($out)) !== false)
        {
            $this->runner->getScore()->merge($score);

            if ($this->runner->getScore()->getAssertionNumber() > 0 || $this->runner->getScore()->getExceptionNumber() > 0 || $this->runner->getScore()->getErrorNumber() > 0 || $this->runner->getScore()->getOutputNumber() > 0)
            {
                $report = new report();
                $output->write($report->handleEvent(atoum\runner::runStop, $this->runner));
            }
        }
        else
        {
            $colorizer = new atoum\cli\colorizer(30, 47);

            if (rtrim($out) == $out)
            {
                $out = $out . $colorizer->colorize('%') . PHP_EOL;
            }

            $output->write($out);
        }

        $this->php->reset();

        return $this;
    }

    protected function getCode($input)
    {
        return
            '<?php' . PHP_EOL .
            'use mageekguy\atoum;' . PHP_EOL .
            'require_once implode(DIRECTORY_SEPARATOR, array(\'' . shell\directory . '\', \'vendor\', \'atoum\', \'atoum\', \'classes\', \'autoloader.php\'));' . PHP_EOL .
            PHP_EOL .
            'class test extends atoum\test {}' . PHP_EOL .
            PHP_EOL .
            '$test = new test();' . PHP_EOL .
            '$assert = new atoum\test\asserter\generator($test);' . PHP_EOL .
            PHP_EOL .
            'try {' . PHP_EOL .
            "\t" . '$result = call_user_func(function() use ($assert) {' . PHP_EOL .
            "\t\t" . $input . ';' . PHP_EOL .
            "\t" . '});' . PHP_EOL
            .'} ' . PHP_EOL .
            'catch (atoum\asserter\exception $exception) {}' . PHP_EOL .
            'catch (\exception $exception) { $test->getScore()->addException(\'<shell code>\', \'<shell>\', \'<process>\', 1, $exception); }' . PHP_EOL .
            PHP_EOL .
            'if ($test->getScore()->getAssertionNumber() > 0 || $test->getScore()->getExceptionNumber() > 0 || $test->getScore()->getErrorNumber() > 0 || $test->getScore()->getOutputNumber() > 0)' . PHP_EOL .
            '{' . PHP_EOL .
            "\t" . 'echo serialize($test->getScore());' . PHP_EOL .
            '}' . PHP_EOL .
            'else if($result)' . PHP_EOL .
            '{' . PHP_EOL .
            "\t" . 'var_export($result);' . PHP_EOL .
            '}' . PHP_EOL
        ;
    }
}
