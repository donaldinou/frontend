<?php

namespace Viteloge\CoreBundle\Component\Enum {

    class TypeEnum extends Enum {

        const __default = null;

        const HOUSE = 'Maison';

        const APPARTMENT = 'Appartement';

        const FIELD = 'Terrain';

        const PARKING = 'Stationnement';

        public function choices() {
            return array(
                self::HOUSE => 'ad.type.house',
                self::APPARTMENT => 'ad.type.appartment',
                self::FIELD => 'ad.type.field',
                self::PARKING => 'ad.type.parking'
            );
        }

    }

}
