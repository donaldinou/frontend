<?php
namespace Acreat\CoreBundle\DBAL {

    class EnumTransactionType extends EnumType {
        protected $name = 'enumtransaction';
        protected $values = array('', 'V', 'L', 'N');
    }

}
