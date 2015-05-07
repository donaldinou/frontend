<?php

namespace Viteloge\CoreBundle\Component\Enum {

    use Acreat\CoreBundle\Component\Enum\SplEnum;

    class CivilityEnum extends SplEnum implements ChoiceInterface {

        const __default = null;

        const MISTER = 'M';

        const MISTRESS = 'Mme';

        const MISS = 'Mlle';

        public function choices() {
            return array(
                self::MISTER => 'user.civility.mister',
                self::MISTRESS => 'user.civility.mistress',
                self::MISS => 'user.civility.miss'
            );
        }

    }

}
