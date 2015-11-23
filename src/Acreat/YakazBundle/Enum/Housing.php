<?php

namespace Acreat\YakazBundle\Enum {

    class Housing/* extends \SplEnum*/ {

        const __default = self::RENTALS;

        const RENTALS = 'housing:rentals';

        const SALES = 'housing:sales';

        const VACATION = 'housing:vacation';

        const COMMERCIALS = 'housing:comercials';

        const ROOMATES = 'housing:roomates';

        public static function getConstList( $include_default=false ) {
            $reflect = new \ReflectionClass(__CLASS__);
            $result = $reflect->getConstants();
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
