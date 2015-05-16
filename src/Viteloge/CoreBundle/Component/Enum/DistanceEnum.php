<?php

namespace Viteloge\CoreBundle\Component\Enum {

    use Acreat\CoreBundle\Component\Enum\SplEnum;

    class DistanceEnum extends SplEnum implements ChoiceInterface {

        const __default = null;

        const FIVE = '5';

        const TEN = '10';

        const TWENTY = '20';

        const THIRTY = '30';

        public function choices() {
            return array(
                self::FIVE => 'viteloge.distance.five',
                self::TEN => 'viteloge.distance.ten',
                self::TWENTY => 'viteloge.distance.twenty',
                self::THIRTY => 'viteloge.distance.thirty'
            );
        }

    }

}
