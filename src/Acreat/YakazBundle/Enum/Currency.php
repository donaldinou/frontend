<?php

namespace Acreat\YakazBundle\Enum {

    class Currency/* extends \SplEnum*/ {

        const __default = self::EURO;

        const EURO = 'euro';

        const DOLLAR = 'dollar';

        const POUND = 'pound';

        public static function getConstList( $include_default=false ) {
            $result = array(
                '__default' => self::__default,
                'EURO' => self::EURO,
                'DOLLAR' => self::DOLLAR,
                'POUND' => self::POUND
            );
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
