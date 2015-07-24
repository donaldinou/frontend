<?php

namespace Viteloge\EstimationBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\Enum\Enum;

    class PathEnum extends Enum {

        const __default = null;

        const DRIVEWAY = 'ALL';

        const DRIVE = 'AV';

        const BOULEVARD = 'BD';

        const CROSSROADS = 'CAR';

        const WAY = 'CHE';

        const PAVEMENT = 'CHS';

        const CITY = 'CITE';

        const CORNICE = 'COR';

        const COURSE = 'CRS';

        const DOMAIN = 'DOM';

        const DESCENT = 'DSC';

        const GAP = 'ECA';

        const ESPLANADE = 'ESP';

        const SUBURB = 'FG';

        const GREAT = 'GR';

        const HAMLET = 'HAM';

        const HALLE = 'HLE';

        const IMPASSE = 'IMP';

        const LOCALITY = 'LD';

        const ALLOTMENT = 'LOT';

        const MARKET = 'MAR';

        const MOUNTED = 'MTE';

        const PASSAGE = 'PAS';

        const SPOT = 'PL';

        const LOWLAND = 'PLN';

        const PLATEAU = 'PLT';

        const WALK = 'PRO';

        const PARVIS = 'PRV';

        const NEIGHBORHOOD = 'QUA';

        const PLATFORM = 'QUAI';

        const RESIDENCE = 'RES';

        const ALLEY = 'RLE';

        const BYPASS = 'ROC';

        const TRAFFIC_CIRCLE = 'RPT';

        const ROAD = 'RTE';

        const STREET = 'RUE';

        const SENTE = 'SEN';

        const SQUARE = 'SQ';

        const MEDIAN = 'TPL';

        const TRAVERSE = 'TRA';

        const VILLA = 'VLA';

        const VILLAGE = 'VLGE';

        public function choices() {
            return array(
                self::DRIVEWAY => 'estimate.path.driveway',
                self::DRIVE => 'estimate.path.drive',
                self::BOULEVARD => 'estimate.path.boulevard',
                self::CROSSROADS => 'estimate.path.crossroads',
                self::WAY => 'estimate.path.way',
                self::PAVEMENT => 'estimate.path.pavement',
                self::CITY => 'estimate.path.city',
                self::CORNICE => 'estimate.path.cornice',
                self::COURSE => 'estimate.path.course',
                self::DOMAIN => 'estimate.path.domain',
                self::DESCENT => 'estimate.path.descent',
                self::GAP => 'estimate.path.gap',
                self::ESPLANADE => 'estimate.path.esplanade',
                self::SUBURB => 'estimate.path.suburb',
                self::GREAT => 'estimate.path.great',
                self::HAMLET => 'estimate.path.hamlet',
                self::HALLE => 'estimate.path.halle',
                self::IMPASSE => 'estimate.path.impasse',
                self::LOCALITY => 'estimate.path.locality',
                self::ALLOTMENT => 'estimate.path.allotment',
                self::MARKET => 'estimate.path.market',
                self::MOUNTED => 'estimate.path.mounted',
                self::PASSAGE => 'estimate.path.passage',
                self::SPOT => 'estimate.path.spot',
                self::LOWLAND => 'estimate.path.lowland',
                self::PLATEAU => 'estimate.path.plateau',
                self::WALK => 'estimate.path.walk',
                self::PARVIS => 'estimate.path.parvis',
                self::NEIGHBORHOOD => 'estimate.path.neighborhood',
                self::PLATFORM => 'estimate.path.platform',
                self::RESIDENCE => 'estimate.path.residence',
                self::ALLEY => 'estimate.path.alley',
                self::BYPASS => 'estimate.path.bypass',
                self::TRAFFIC_CIRCLE => 'estimate.path.trafficcircle',
                self::ROAD => 'estimate.path.road',
                self::STREET => 'estimate.path.street',
                self::SENTE => 'estimate.path.sente',
                self::SQUARE => 'estimate.path.square',
                self::MEDIAN => 'estimate.path.median',
                self::TRAVERSE => 'estimate.path.traverse',
                self::VILLA => 'estimate.path.villa',
                self::VILLAGE => 'estimate.path.village'
            );
        }

    }

}
