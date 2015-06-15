<?php

namespace Viteloge\CoreBundle\SearchEntity {

    use Symfony\Component\HttpFoundation\Request;
    use GeoIp2\Database\Reader;

    /**
     *
     */
    class Ad {

        /**
         *
         */
        protected $transaction;

        /**
         *
         */
        protected $where;

        /**
         *
         */
        protected $whereArea;

        /**
         *
         */
        protected $whereDepartment;

        /**
         *
         */
        protected $whereState;

        /**
         *
         */
        protected $what;

        /**
         *
         */
        protected $rooms;

        /**
         *
         */
        protected $minPrice;

        /**
         *
         */
        protected $maxPrice;

        /**
         *
         */
        protected $keywords;

        /**
         *
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
        public function handleRequest(Request $request) {
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
            $this->transaction = strtoupper($value);
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
                foreach ($value as $key => $entity) {
                    if (is_object($entity)) {
                        $value[$key] = $entity->getId();
                    }
                }
                $this->where = $value;
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
                $this->whereArea = $value;
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
                $this->whereDepartment = $value;
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
                $this->whereState = $value;
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
                $this->what = array_map(
                    function($str){
                        return /*strtolower(*/trim($str)/*)*/;
                    },
                    $value
                );
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
                $this->rooms = $value;
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
