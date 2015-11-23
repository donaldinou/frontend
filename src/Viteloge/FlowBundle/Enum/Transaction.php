<?php

namespace Viteloge\FlowBundle\Enum {

    use Acreat\MitulaBundle\Enum\AdType as MitulaAdType;
    use Acreat\TrovitBundle\Enum\AdType as TrovitAdType;
    use Acreat\YakazBundle\Enum\Housing;

    class Transaction/* extends SplEnum*/ {

        const _default = null;

        const SALE = 'V';

        const RENT = 'R';

        const NEWED = 'N';

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

        public static function getMitulaConstant($value) {
            switch ($value) {
                case self::RENT:
                    $result = MitulaAdType::RENT;
                    break;

                case self::SALE:
                default:
                    $result = MitulaAdType::SALE;
                    break;
            }
            return $result;
        }

        public static function getTrovitConstant($value) {
            switch ($value) {
                case self::RENT:
                    $result = TrovitAdType::FOR_RENT;
                    break;

                case self::SALE:
                case self::NEWED:
                default:
                    $result = TrovitAdType::FOR_SALE;
                    break;
            }
            return $result;
        }

        public static function getYakazConstant($value) {
            switch ($value) {
                case self::RENT:
                    $result = Housing::RENTALS;
                    break;

                case self::SALE:
                case self::NEWED:
                default:
                    $result = Housing::SALES;
                    break;
            }
            return $result;
        }

    }

}
