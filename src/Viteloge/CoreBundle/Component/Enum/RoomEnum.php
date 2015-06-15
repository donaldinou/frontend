<?php

namespace Viteloge\CoreBundle\Component\Enum {

    class RoomEnum extends Enum {

        const __default = null;

        const ONE = 1;

        const TWO = 2;

        const THREE = 3;

        const FOUR = 4;

        const MORE = 5;

        public function choices() {
            return array(
                self::ONE => 'ad.rooms.one',
                self::TWO => 'ad.rooms.two',
                self::THREE => 'ad.rooms.three',
                self::FOUR => 'ad.rooms.four',
                self::MORE => 'ad.rooms.more'
            );
        }

    }

}
