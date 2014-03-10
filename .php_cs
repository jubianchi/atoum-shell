<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . DIRECTORY_SEPARATOR . 'classes')
;

return Symfony\CS\Config\Config::create()
    ->fixers(array('unused_use', 'linefeed', 'eof_ending', 'trailing_spaces'))
    ->finder($finder)
;
