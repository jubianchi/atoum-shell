<?php

namespace atoum\shell;

use mageekguy\atoum;
use mageekguy\atoum\report\fields;

class report extends atoum\reports\asynchronous
{
    public function __construct()
    {
        parent::__construct();

        $redColorizer = new atoum\cli\colorizer(31);
        $boldColorizer = new atoum\cli\colorizer('1;37');
        $redRibbonColorizer = new atoum\cli\colorizer(37, 41);
        $greenRibbonColorizer = new atoum\cli\colorizer(37, 42);

        $failures = new fields\runner\failures\cli();
        $this->addField($failures->setTitleColorizer($redColorizer));

        $exceptions = new fields\runner\exceptions\cli();
        $this->addField($exceptions->setTitleColorizer($redColorizer));

        $errors = new fields\runner\errors\cli();
        $this->addField($errors->setTitleColorizer($redColorizer));

        $result = new fields\runner\result\cli();
        $this->addField(
            $result
                ->setSuccessColorizer($greenRibbonColorizer)
                ->setFailureColorizer($redRibbonColorizer)
        );

        $output = new fields\runner\outputs\cli();
        $this->addField($output->setTitleColorizer($boldColorizer));
    }
}
