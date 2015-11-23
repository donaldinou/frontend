<?php

namespace Acreat\MitulaBundle\Enum {

    class Volume/* extends \SplEnum*/ {

        const __default = self::METERS;

        const METERS = 'meters';

        const SQFT = 'sqft';

        public static function getConstList( $include_default=false ) {
            $result = array(
                '__default' => self::__default,
                'METERS' => self::METERS
            );
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
