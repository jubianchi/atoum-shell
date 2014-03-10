<?php

namespace atoum\shell\killable;

use atoum\shell\killable;

class pool implements killable
{
    protected $items = array();

    public function add(killable $killable)
    {
        $this->items[] = $killable;

        return $this;
    }

    public function remove(killable $killable)
    {
        if (($key = array_search($killable, $this->items)) !== false)
        {
            unset($this->items[$key]);
            $this->items = array_values($this->items);
        }
    }

    public function kill($signal = null)
    {
        $killable = array_pop($this->items);

        if ($killable)
        {
            return $killable->kill($signal);
        }

        return false;
    }
}
