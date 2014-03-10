<?php

namespace atoum\shell;


interface killable
{
    public function kill($signal = null);
}
