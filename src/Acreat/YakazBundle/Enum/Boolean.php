<?php

namespace Acreat\YakazBundle\Enum {

    class Boolean/* extends \SplBool*/ {

        const __default = self::false;

        const false = 'No';

        const true = 'Yes';

        public static function getConstList( $include_default=false ) {
            $result = array(
                '__default' => self::__default,
                'false' => self::false,
                'true' => self::true
            );
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
