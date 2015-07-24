<?php

namespace Viteloge\EstimationBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\Enum\Enum;

    class ExpositionEnum extends Enum {

        const __default = null;

        const NORTH = 'N';

        const SOUTH = 'S';

        const WEST = 'O';

        const EAST = 'E';

        public function choices() {
            return array(
                self::NORTH => 'estimate.exposition.north',
                self::SOUTH => 'estimate.exposition.south',
                self::WEST  => 'estimate.exposition.west',
                self::EAST  => 'estimate.exposition.east'
            );
        }

    }

}
