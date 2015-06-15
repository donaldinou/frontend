<?php

namespace Viteloge\CoreBundle\Component\Enum {

    use Acreat\CoreBundle\Component\Enum\SplEnum;

    abstract class Enum extends SplEnum implements ChoiceInterface {

        public abstract function choices();

        public static function getValues() {
            $object = new static();
            return $object->getConstList();
        }

    }
}
