<?php

namespace Viteloge\CoreBundle\SearchEntity {

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Validator\Constraints as Assert;
    use GeoIp2\Database\Reader;
    use Viteloge\CoreBundle\Component\Enum\TransactionEnum;

    /**
     *
     */
    class Ad {

        /**
         *
         */
        protected $transaction;

        /**
         * @Assert\Expression(
         *     "this.getWhereArea() or this.getWhereDepartment() or this.getWhereState() or value",
         *     message="assert.expression.ad.validate.where"
         * )
         * @Assert\Count(
         *      min = "1",
         *      max = "5",
         *      minMessage = "assert.count.ad.validate.min.where",
         *      maxMessage = "assert.count.ad.validate.max.where"
         * )
         * @Assert\Type(
         *     type="array"
         * )
         */
        protected $where;

        /**
         * @Assert\Type(
         *     type="array"
         * )
         */
        protected $whereArea;

        /**
         * @Assert\Type(
         *     type="array"
         * )
         */
        protected $whereDepartment;

        /**
         * @Assert\Type(
         *     type="array"
         * )
         */
        protected $whereState;

        /**
         * @Assert\Choice(
         *      callback = {"Viteloge\CoreBundle\Component\Enum\TypeEnum", "getValues"},
         *      multiple = true
         * )
         */
        protected $what;

        /**
         * @Assert\Choice(
         *      callback = {"Viteloge\CoreBundle\Component\Enum\RoomEnum", "getValues"},
         *      multiple = true
         * )
         */
        protected $rooms;

        /**
         *
         * @Assert\GreaterThanOrEqual(
         *     value = 0
         * )
         * @Assert\Expression(
         *     "value === null or this.getMaxPrice() == null or value <= this.getMaxPrice()",
         *     message="assert.expression.ad.validate.minprice"
         * )
         * @Assert\Type(
         *     type="float"
         * )
         */
        protected $minPrice;

        /**
         * @Assert\GreaterThanOrEqual(
         *     value = 0
         * )
         * @Assert\Expression(
         *     "value === null or this.getMinPrice() == null or value >= this.getMinPrice()",
         *     message="assert.expression.ad.validate.maxprice"
         * )
         * @Assert\Type(
         *     type="float"
         * )
         */
        protected $maxPrice;

        /**
         * @Assert\Length(
         *      max = 255
         * )
         */
        protected $keywords;

        /**
         * @Assert\Choice(
         *      callback = {"Viteloge\CoreBundle\Component\Enum\DistanceEnum", "getValues"},
         *      multiple = false,
         * )
         */
        protected $radius;

        /**
         *
         */
        protected $location;

        /**
         *
         */
        protected $privilegeRank;

        /**
         *
         */
        protected $order;

        /**
         *
         */
        protected $sort;

        /**
         *
         */
        protected $direction;

        /**
         *
         */
        public function __construct() {
            $this->setDirection('desc');
        }

        /**
         *
         */
        public function handleRequest(Request $request=null) {
            if ($request instanceof Request) {
                $queries = array_merge(
                    $request->query->all(),
                    $request->request->all()
                );
                foreach ($queries as $key => $value) {
                    if (method_exists($this, 'set'.ucfirst($key))) {
                        $this->{'set'.ucfirst($key)}($value);
                    }
                }
                $this->geoLocalize($request);
            }
        }

        /**
         *
         */
        protected function geoLocalize(Request $request) {
            $adRadius = $this->getRadius();
            $adLocation = $this->getLocation();
            if (empty($adLocation) && !empty($adRadius)) {
                $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-City.mmdb', array($request->getLocale()));
                try {
                    $ip = $request->getClientIp();
                    $record = $reader->city($ip);
                    $lat = $record->location->latitude;
                    $lng = $record->location->longitude;
                } catch (\Exception $e) { // \GeoIp2\Exception\AddressNotFoundException
                    $lat = 48.86;
                    $lng = 2.35;
                }
                $this->setLocation($lat.','.$lng);
            }
        }

        /**
         *
         */
        public function getTransaction() {
            return $this->transaction;
        }

        /**
         *
         */
        public function setTransaction($value) {
            if(isset($value[0])){
              $value = $value[0];
              $value = strtoupper($value);
            }else{
                $value = null;
            }


            if ($value == TransactionEnum::__default || $value == 'default') {
                $value = null;
            }
            if (strlen($value)>1) {
                $value = TransactionEnum::getAlias($value);
            }
            $val = array($value);
            $this->transaction = $val;
            return $this;
        }

        /**
         *
         */
        public function getWhere() {
            return $this->where;
        }

        /**
         *
         */
        public function setWhere($value) {
            if (!empty($value)) {
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->where = array_filter(array_map(
                    function($item) {
                        if(is_string($item)) {
                            return strtolower($item);
                        }
                        elseif (is_object($item)) {
                            return $item->getId();
                        }
                    }, $value
                ));
            }
            return $this;
        }

        /**
         *
         */
        public function getWhereArea() {
            return $this->whereArea;
        }

        /**
         *
         */
        public function setWhereArea($value) {
            if (!empty($value)) {
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->whereArea = array_filter(array_map('strtolower', $value));
            }
            return $this;
        }

        /**
         *
         */
        public function getWhereDepartment() {
            return $this->whereDepartment;
        }

        /**
         *
         */
        public function setWhereDepartment($value) {
            if (!empty($value)) {
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->whereDepartment = array_filter(array_map('strtolower', $value));
            }
            return $this;
        }

        /**
         *
         */
        public function getWhereState() {
            return $this->whereState;
        }

        /**
         *
         */
        public function setWhereState($value) {
            if (!empty($value)) {
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->whereState = array_filter(array_map('strtolower', $value));
            }
            return $this;
        }

        /**
         *
         */
        public function getWhat() {
            return $this->what;
        }

        /**
         *
         */
        public function setWhat($value) {
            if (!empty($value)) {
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->what = array_filter(array_map(
                    function($str){
                        return /*strtolower(*/trim(ucfirst($str))/*)*/;
                    },
                    $value
                ));
            }
            return $this;
        }

        /**
         *
         */
        public function getRooms() {
            return $this->rooms;
        }

        /**
         *
         */
        public function setRooms($value) {
            if (!empty($value)) {
                if (!is_array($value)) {
                    $value = explode(',', $value);
                }
                $this->rooms = array_filter($value);
            }
            return $this;
        }

        /**
         *
         */
        public function getMinPrice() {
            return $this->minPrice;
        }

        /**
         *
         */
        public function setMinPrice($value) {
            if (!empty($value)) {
                $this->minPrice = $value;
            }
            return $this;
        }

        /**
         *
         */
        public function getMaxPrice() {
            return $this->maxPrice;
        }

        /**
         *
         */
        public function setMaxPrice($value) {
            if (!empty($value)) {
                $this->maxPrice = $value;
            }
            return $this;
        }

        /**
         *
         */
        public function getKeywords() {
            return $this->keywords;
        }

        /**
         *
         */
        public function setKeywords($value) {
            if (!empty($keyword)) {
                $this->keywords = $value;
            }
            return $this;
        }

        /**
         *
         */
        public function getRadius() {
            return $this->radius;
        }

        /**
         *
         */
        public function setRadius($value) {
            if (!empty($value)) {
                $this->radius = $value;
            }
            return $this;
        }

        /**
         *
         */
        public function getLocation() {
            return $this->location;
        }

        /**
         *
         */
        public function setLocation($value) {
            if (!empty($value)) {
                $this->location = $value;
            }
            return $this;
        }

        /**
         *
         */
        public function getSort() {
            return $this->sort;
        }

        /**
         *
         */
        public function setSort($value) {
            $this->sort = $value;
            return $this;
        }

        /**
         *
         */
        public function getDirection() {
            return $this->direction;
        }

        /**
         *
         */
        public function setDirection($value) {
            $this->direction = strtolower($value);
            return $this;
        }

    }

}
