<?php

namespace Viteloge\EstimationBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\Enum\Enum;

    class ConditionEnum extends Enum {

        const __default = null;

        const NEWER = 'N';

        const GOOD = 'B';

        const WORK = 'T';

        const REPAIR = 'R';

        public function choices() {
            return array(
                self::NEWER  => 'estimate.condition.newer',
                self::GOOD   => 'estimate.condition.good',
                self::WORK   => 'estimate.condition.work',
                self::REPAIR => 'estimate.condition.repair'
            );
        }

    }

}
