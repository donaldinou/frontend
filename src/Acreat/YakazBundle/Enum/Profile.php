<?php

namespace Acreat\YakazBundle\Enum {

    class Profile/* extends \SplEnum*/ {

        const __default = self::rentals;

        const individual = 'profile:individual';

        const commercial = 'profile:commercial';

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
