<?php

namespace Viteloge\CoreBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;

    class TransactionEnum extends Enum {

        const __default = '';

        const RENT = EnumTransactionType::RENT;

        const SALE = EnumTransactionType::SALE;

        const NEWER = EnumTransactionType::NEWER;

        public function choices() {
            return array(
                self::SALE => 'ad.sale',
                self::RENT => 'ad.rent',
                self::NEWER => 'ad.new',
            );
        }

        /**
         * Use for legacy support
         */
        public static function getAlias($alias) {
            switch (strtolower($alias)) {
                case 'vente':
                    return self::SALE;
                    break;
                case 'location':
                    return self::RENT;
                    break;
                default:
                    return self::__default;
                    break;
            }
        }

        public static function getValues() {
            $object = new static();
            return $object->getConstList(true);
        }

    }

}
