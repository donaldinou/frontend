<?php

namespace Viteloge\CoreBundle\Component\Enum {

    use Acreat\CoreBundle\Component\Enum\SplEnum;

    class RoomEnum extends SplEnum implements ChoiceInterface {

        const __default = null;

        const ONE = 1;

        const TWO = 2;

        const THREE = 3;

        const FOUR = 4;

        const MORE = 5;

        public function choices() {
            return array(
                self::ONE => 'ad.room.one',
                self::TWO => 'ad.room.two',
                self::THREE => 'ad.room.three',
                self::FOUR => 'ad.room.four',
                self::MORE => 'ad.room.more'
            );
        }

    }

}
