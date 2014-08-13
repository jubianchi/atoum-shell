<?php

$color['blue'] = new \mageekguy\atoum\cli\colorizer('38;5;111', '48;5;16');
$inverse['blue'] = new \mageekguy\atoum\cli\colorizer('38;5;16');

$color['blue2'] = new \mageekguy\atoum\cli\colorizer('38;5;153', '48;5;16');
$inverse['blue2'] = new \mageekguy\atoum\cli\colorizer('38;5;16', '48');

$script->getRunner()
    ->getReport()
    ->setPrompt(new \mageekguy\atoum\shell\cli\prompt(function() use ($script, $color, $inverse) {
        $home = getenv('HOME');
        $base = __DIR__;

        $pwd = preg_replace('/^' . preg_quote($base, '/') . '/', '$ATOUMSHELL', getcwd());
        $pwd = preg_replace('/^' . preg_quote($home, '/') . '/', '~', $pwd);

        $version = phpversion();

        return
            $color['blue2']->colorize(' php ' . $version . ' ') . $inverse['blue2']->colorize('⮀') .
            $color['blue']->colorize(' ' . $pwd . ' ') . $inverse['blue']->colorize('⮀') . ' '
        ;
    }))
;
