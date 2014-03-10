<?php

namespace atoum\shell\reports\realtime;

use
	mageekguy\atoum,
	atoum\shell\runner,
	mageekguy\atoum\reports\realtime,
    atoum\shell\cli,
    atoum\shell\report\fields
;

class shell extends realtime
{
    protected $prompt;

	public function __construct(runner $runner)
	{
		parent::__construct();

		$this
            ->addField(new fields\shell\runner\welcome($runner))
            ->addField($this->prompt = new fields\shell\runner\prompt($runner))
            ->addField(new fields\shell\runner\exception($runner))
            ->addField(new fields\shell\runner\goodbye($runner))
            ->addField(new fields\shell\runner\kill($runner))
        ;
	}

    public function setPrompt(cli\prompt $prompt = null)
    {
        $this->prompt->setPrompt($prompt ?: new cli\prompt('> '));

        return $this;
    }

    public function getPrompt()
    {
        return $this->prompt->getPrompt();
    }
}
