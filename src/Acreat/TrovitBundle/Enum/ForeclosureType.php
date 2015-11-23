<?php

namespace Acreat\TrovitBundle\Enum {

    class ForeclosureType/* extends \SplEnum*/ {

        const __default = null;

        const PRE_FORECLOSURE = 'Pre Foreclosure';

        const AUCTION = 'Auction';

        const BANK_OWNED = 'Bank Owned';

        public static function getConstList( $include_default=false ) {
            $result = array(
                '__default' => self::__default,
                'PRE_FORECLOSURE' => self::PRE_FORECLOSURE,
                'AUCTION' => self::AUCTION,
                'BANK_OWNED' => self::BANK_OWNED
            );
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
