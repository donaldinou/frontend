<?php

namespace Viteloge\CoreBundle\Entity {

    /**
     *
     */
    class Privilege  {

        /**
         *
         */
        protected $rank;

        /**
         *
         */
        protected $agency;

        /**
         *
         */
        protected $logo;

        /**
         *
         */
        protected $photo;

        /**
         *
         */
        protected $bold;

        /**
         *
         */
        protected $bgColor;

        /**
         *
         */
        protected $exclu;

        /**
         *
         */
        public function __construct($code) {
            if (!is_string($code)) {
                throw new \InvalidArgumentException('code expected to be a string. '.gettype($code).' given.');
            }
            $this->setRank((int)substr($code, 0, 1));
            $this->setAgency((int)substr($code, 1, 1));
            $this->setLogo((int)substr($code, 2, 1));
            $this->setPhoto((int)substr($code, 3, 1));
            $this->setBold((int)substr($code, 4, 1));
            $this->setBgColor((int)substr($code, 5, 1));
            $this->setExclu((int)substr($code, 6, 1));
        }

        /**
         *
         */
        public function setRank($value) {
            $this->rank = (bool)$value;
        }

        /**
         *
         */
        public function isRank() {
            return $this->rank;
        }

        /**
         *
         */
        public function setAgency($value) {
            $this->agency = (bool)$value;
        }

        /**
         *
         */
        public function isAgency() {
            return $this->agency;
        }

        /**
         *
         */
        public function setLogo($value) {
            $this->logo = (bool)$value;
        }

        /**
         *
         */
        public function hasLogo() {
            return $this->logo;
        }

        /**
         *
         */
        public function setPhoto($value) {
            $this->photo = (bool)$value;
        }

        /**
         *
         */
        public function hasPhoto() {
            return $this->photo;
        }

        /**
         *
         */
        public function setBold($value) {
            $this->bold = (bool)$value;
        }

        /**
         *
         */
        public function isBold() {
            return $this->bold;
        }

        /**
         *
         */
        public function setBgColor($value) {
            $this->bgColor = (bool)$value;
        }

        /**
         *
         */
        public function hasBgColor() {
            return $this->bgColor;
        }

        /**
         *
         */
        public function setExclu($value) {
            $this->exclu = (bool)$value;
        }

        /**
         *
         */
        public function isExclu() {
            return $this->exclu;
        }

    }

}
