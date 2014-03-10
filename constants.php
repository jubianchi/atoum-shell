<?php

namespace atoum\shell;

if (defined(__NAMESPACE__ . '\running') === false)
{
    define(__NAMESPACE__ . '\running',  true);
    define(__NAMESPACE__ . '\directory', __DIR__);
    define(__NAMESPACE__ . '\version', preg_replace('/\$Rev: ([^ ]+) \$/', '$1', '$Rev: DEVELOPMENT-0.0.1 $'));
    define(__NAMESPACE__ . '\author', 'Julien Bianchi');
    define(__NAMESPACE__ . '\mail', 'contact@jubianchi.fr');
    define(__NAMESPACE__ . '\repository',  'https://github.com/atoum/shell');
}
