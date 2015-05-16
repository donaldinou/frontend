<?php

namespace Viteloge\CoreBundle\SearchEntity {

    use Symfony\Component\HttpFoundation\Request;

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
        protected $sort;

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
        protected $direction;

        /**
         *
         */
        public function __construct() {

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
            $this->transaction = $value;
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
            if (!is_array($value)) {
                $value = explode(',', $value);
            }
            $this->where = $value;
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
            if (!is_array($value)) {
                $value = explode(',', $value);
            }
            $this->what = $value;
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
            if (!is_array($value)) {
                $value = explode(',', $value);
            }
            $this->rooms = $value;
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
            $this->minPrice = $value;
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
            $this->maxPrice = $value;
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
            $this->keywords = $value;
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
            $this->radius = $value;
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
            $this->direction = $value;
        }

    }

}
