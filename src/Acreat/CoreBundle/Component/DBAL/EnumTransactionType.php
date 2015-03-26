<?php
namespace Acreat\CoreBundle\Component\DBAL;

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
        return self::getType('enumtransaction')->values;
    }
}
