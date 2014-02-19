<?php

namespace atoum\shell;

use Hoa\Console\Readline\Readline;
use mageekguy\atoum;
use mageekguy\atoum\report\fields\runner\exceptions;
use mageekguy\atoum\report\fields\runner\failures;
use mageekguy\atoum\report\fields\runner\errors;
use mageekguy\atoum\report\fields\runner\result;
use mageekguy\atoum\report\fields\runner\duration;
use mageekguy\atoum\report\fields\runner\outputs;

require_once implode(DIRECTORY_SEPARATOR, array(__DIR__, 'vendor', 'autoload.php'));
require_once implode(DIRECTORY_SEPARATOR, array(__DIR__, 'vendor', 'atoum', 'atoum', 'classes', 'autoloader.php'));

class test extends atoum\test {}
class runner implements atoum\observable
{
    protected $score;

    public function setScore($score)
    {
        $this->score = $score;
    }

    function getScore()
    {
        return $this->score;
    }

    function getTestNumber()
    {
        return 1;
    }

    function getTestMethodNumber()
    {
        return 1;
    }

    public function callObservers($event)
    {
        return $this;
    }

    public function getBootstrapFile()
    {
        return null;
    }
}

class shell
{
	protected $generator;
	protected $runner;
	protected $readline;
	protected $inputPrompt;
	protected $outputPrompt;
    protected $context = array();

	public function __construct(atoum\asserter\generator $generator = null)
	{
		$this->generator = $generator ?: new atoum\test\asserter\generator(new test());
		$this->runner = new atoum\runner();
        $this->readline = new Readline();
        $this->inputPrompt = new atoum\cli\prompt('> ');
        $this->outputPrompt = new atoum\cli\prompt('=> ');
	}

    public function read()
    {
        return $this->readline->readLine((string) $this->inputPrompt);
    }

    public function write($string)
    {
        return $this->outputPrompt . $string;
    }

    public function run()
    {
        echo (string) new atoum\report\fields\runner\atoum\logo();
        echo '             Welcome to the atoum interactive shell !' . PHP_EOL . PHP_EOL;

        while (true)
        {
            $input = $this->read();
            $output = $this->execute($input);

            if ($output !== null)
            {
                echo $this->write($output) . PHP_EOL;
            }

            /** @var \mageekguy\atoum\test\score $score */
            $score = $this->generator->getTest()->getScore();
            $this->runner->getScore()->merge($score);

            if ($this->runner->getScore()->getAssertionNumber() > 0 || $this->runner->getScore()->getExceptionNumber() > 0 || $this->runner->getScore()->getErrorNumber() > 0 || $this->runner->getScore()->getOutputNumber() > 0)
            {
                $failures = new failures\cli();
                $failures
                    ->setTitleColorizer(new atoum\cli\colorizer(31))
                    ->handleEvent(atoum\runner::runStop, $this->runner)
                ;
                echo $failures;

                $exceptions = new exceptions\cli();
                $exceptions
                    ->setTitleColorizer(new atoum\cli\colorizer(31))
                    ->handleEvent(atoum\runner::runStop, $this->runner)
                ;
                echo $exceptions;

                $errors = new errors\cli();
                $errors
                    ->setTitleColorizer(new atoum\cli\colorizer(31))
                    ->handleEvent(atoum\runner::runStop, $this->runner)
                ;
                echo $errors;

                $result = new result\cli();
                $result
                    ->setSuccessColorizer(new atoum\cli\colorizer(37, 42))
                    ->setFailureColorizer(new atoum\cli\colorizer(37, 41))
                    ->handleEvent(atoum\runner::runStop, $this->runner)
                ;
                echo $result;

                $output = new outputs\cli();
                $output
                    ->setTitleColorizer(new atoum\cli\colorizer('1;37'))
                    ->handleEvent(atoum\runner::runStop, $this->runner)
                ;
                echo $output;
            }

            $this->runner->getScore()->reset();
            $score->reset();
        }

    }

    public function __set($name, $value)
    {
        $this->context[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->context[$name]) ? $this->context[$name] : null;
    }

    public function execute($code)
    {
        /** @var \mageekguy\atoum\test\score $score */
        $score = $this->generator->getTest()->getScore();

        try
        {
            $runner = $this->runner;
            $context = $this;
            $assert = $this->generator->getTest();
            $run = function($test) use($runner) {
                $runner
                    ->resetTestPaths()
                    ->addTest($test)
                    ->run()
                ;
            };

            set_error_handler(
                function($errno, $errstr, $file, $line) use($score) {
                    $score->addError('<shell code>', __CLASS__, 'execute', $errno, $errstr, $file, $line);
                },
                E_ALL | E_STRICT
            );

            //if ($buffering === true)
            {
                //ob_start();
            }

            $result = eval($code);

            //if ($buffering === true)
            {
                $output = ob_get_clean();

                if ($output)
                {
                    $score->addOutput('<shell code>', __CLASS__, __FUNCTION__, ob_get_clean());
                }
            }

            restore_error_handler();

            return $result !== null ? var_export($result, true) : null;
        }
        catch(atoum\asserter\exception $exception) {}
        catch(\exception $exception)
        {
            $score->addException('<shell code>', __CLASS__, __FUNCTION__, 1, $exception);
        }
    }
}

//fclose(STDERR);
$shell = new shell();
$shell->run();