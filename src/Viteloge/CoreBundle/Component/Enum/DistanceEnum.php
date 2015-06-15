<?php

namespace Viteloge\CoreBundle\Component\Enum {

    class DistanceEnum extends Enum {

        const __default = null;

        const NONE = 0;

        const FIVE = 5;

        const TEN = 10;

        const TWENTY = 20;

        const THIRTY = 30;

        public function choices() {
            return array(
                self::NONE => 'ad.distance.none',
                self::FIVE => 'ad.distance.five',
                self::TEN => 'ad.distance.ten',
                self::TWENTY => 'ad.distance.twenty',
                self::THIRTY => 'ad.distance.thirty'
            );
        }

    }

}
