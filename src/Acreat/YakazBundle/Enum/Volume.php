<?php

namespace Acreat\YakazBundle\Enum {

    class Volume/* extends \SplEnum*/ {

        const __default = self::M2;

        const M2 = 'm2';

        const SQFT = 'sqft';

        public static function getConstList( $include_default=false ) {
            $result = array(
                '__default' => self::__default,
                'M2' => self::M2,
                'SQFT' => self::SQFT
            );
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
