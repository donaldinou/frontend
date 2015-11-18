<?php

namespace Viteloge\EstimationBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\Enum\Enum;

    class TimeEnum extends Enum {

        const __default = null;

        const IMMEDIATE = 0;

        const ONE_MONTH = 1;

        const TWO_MONTH = 2;

        const SIX_MONTH = 6;

        const NONE = -1;

        public function choices() {
            return array(
                self::IMMEDIATE => 'estimate.time.immediate',
                self::ONE_MONTH => 'estimate.time.one_month',
                self::TWO_MONTH => 'estimate.time.two_month',
                self::SIX_MONTH => 'estimate.time.six_month',
                self::NONE => 'estimate.time.none'
            );
        }

    }

}
