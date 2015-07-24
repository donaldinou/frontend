<?php

namespace Viteloge\EstimationBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\Enum\Enum;

    class UsageEnum extends Enum {

        const __default = null;

        const INHABITED = 'H';

        const RENT = 'L';

        const BLANK = 'V';

        public function choices() {
            return array(
                self::INHABITED => 'estimate.usage.inhabited',
                self::RENT => 'estimate.usage.rent',
                self::BLANK => 'estimate.usage.empty'
            );
        }

    }

}
