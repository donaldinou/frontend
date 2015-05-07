<?php

namespace Viteloge\CoreBundle\Component\Enum {

    use Acreat\CoreBundle\Component\Enum\SplEnum;

    class TypeEnum extends SplEnum implements ChoiceInterface {

        const __default = null;

        const HOUSE = 'Maison';

        const APPARTMENT = 'Appartement';

        const FIELD = 'Terrain';

        const PARKING = 'Stationnement';

        public function choices() {
            return array(
                self::HOUSE => 'ad.type.house',
                self::APPARTMENT => 'ad.type.appartement',
                self::FIELD => 'ad.type.field',
                self::PARKING => 'ad.type.parking'
            );
        }

    }

}
