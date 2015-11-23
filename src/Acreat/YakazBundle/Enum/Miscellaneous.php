<?php

namespace Acreat\YakazBundle\Enum {

    class Miscellaneous/* extends \SplEnum*/ {

        const __default = self::MISCELLANEOUS;

        const MISCELLANEOUS = 'miscellaneous';

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
