<?php
namespace Viteloge\CoreBundle\Component\DBAL {

    use Acreat\CoreBundle\Component\DBAL\EnumType;

    class EnumTransactionType extends EnumType {
        const __default = self::RENT_VALUE;

        const SALE_VALUE = 'V';
        const RENT_VALUE = 'L';
        const NEWER_VALUE = 'N';

        const SALE_LABEL = 'Sale';
        const RENT_LABEL = 'Rent';
        const NEWER_LABEL = 'New';

        protected $name = 'enumtransaction';
        protected $values = array(
            self::RENT_VALUE => self::RENT_LABEL,
            self::SALE_VALUE => self::SALE_LABEL,
            self::NEWER_VALUE => self::NEWER_LABEL
        );

        public static function getValues() {
            if (!\Doctrine\DBAL\Types\Type::hasType('enumtransaction')) {
                \Doctrine\DBAL\Types\Type::addType('enumtransaction', __CLASS__);
            }
            return self::getType('enumtransaction')->values;
        }
    }

}
