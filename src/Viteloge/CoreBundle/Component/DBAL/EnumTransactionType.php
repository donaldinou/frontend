<?php
namespace Viteloge\CoreBundle\Component\DBAL {

    use Acreat\CoreBundle\Component\DBAL\EnumType;

    class EnumTransactionType extends EnumType {
        const __default = self::RENT;
        const SALE = 'V';
        const RENT = 'L';
        const NEWER = 'N';

        protected $name = 'enumtransaction';
        protected $values = array(
            self::RENT,
            self::SALE,
            self::NEWER
        );

        public static function getValues() {
            if (!\Doctrine\DBAL\Types\Type::hasType('enumtransaction')) {
                \Doctrine\DBAL\Types\Type::addType('enumtransaction', __CLASS__);
            }
            return self::getType('enumtransaction')->values;
        }
    }

}
