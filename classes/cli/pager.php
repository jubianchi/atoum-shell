<?php

namespace atoum\shell\cli;

use Hoa\Console\Processus;

class pager extends \Hoa\Console\Chrome\Pager
{
    public static function less($output, $mode)
    {
        return static::pager($output, $mode, static::LESS);
    }

    public static function pager($output, $mode, $type = null)
    {
        static $process = null;

        if ($mode & PHP_OUTPUT_HANDLER_START)
        {
            $pager = null !== $type ? Processus::locate($type) : (isset($_ENV['PAGER']) ? $_ENV['PAGER'] : null);

            if (null === $pager)
            {
                return $output;
            }

            $process = new Processus($pager, array('-FRSX'), array(0 => array('pipe', 'r')));
            $process->open();
        }

        $process->writeAll($output);

        if ($mode & PHP_OUTPUT_HANDLER_FINAL)
        {
            $process->close();
        }

        return null;
    }
}
