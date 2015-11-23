<?php

namespace Acreat\MitulaBundle\Enum {

    class AdType/* extends SplEnum*/ {

        const __default = null;

        const RENT = 'Rent';

        const SALE = 'Sale';

        public static function getConstList( $include_default=false ) {
            //$reflect = new \ReflectionClass(get_class($this));
            //return $reflect->getConstants());
            $result = array(
                '__default' => self::__default,
                'RENT' => self::RENT,
                'SALE' => self::SALE
            );
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
