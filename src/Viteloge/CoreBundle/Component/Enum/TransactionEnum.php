<?php

namespace Viteloge\CoreBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;

    class TransactionEnum extends Enum {

        const __default = 'DEFAULT';

        const RENT = EnumTransactionType::RENT;

        const SALE = EnumTransactionType::SALE;

        const NEWER = EnumTransactionType::NEWER;

        public function choices() {
            return array(
                self::RENT => 'ad.rent',
                self::SALE => 'ad.sale',
                self::NEWER => 'ad.new'
            );
        }

    }

}
