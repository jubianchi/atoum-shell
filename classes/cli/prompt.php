<?php

namespace atoum\shell\cli;

use mageekguy\atoum\cli;

class prompt extends cli\prompt
{
	public function __toString()
	{
        $value = $this->value;

        if (is_callable($value) === true)
        {
            $value = $value();
        }

		return $this->colorizer->colorize($value);
	}

	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}
}
