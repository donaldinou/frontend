<?php

namespace Viteloge\FrontendBundle\Component\Sitemap {

    class Element {

        protected $name;

        protected $section;

        protected $description;

        protected $loc;

        protected $lastmod;

        protected $changefreq;

        protected $priority;

        protected $child;

        protected $childrenLoc;

        public function __construct() {
            $this->child = false;
        }

        public function getName() {
            return $this->name;
        }

        public function setName($name) {
            $this->name = $name;
            return $this;
        }

        public function getSection($section) {
            return $this->section;
        }

        public function setSection($section) {
            $this->section = $section;
            return $this;
        }

        public function getDescription() {
            return $this->description;
        }

        public function setDescription( $description ) {
            $this->description = $description;
            return $this;
        }

        public function getLoc() {
            return $this->loc;
        }

        public function setLoc($loc) {
            $this->loc = $loc;
            return $this;
        }

        public function getLastmod() {
            return $this->lastmod;
        }

        public function setLastmod( $lastmod ) {
            $this->lastmod = $lastmod;
            return $this;
        }

        public function getChangefreq() {
            return $this->changefreq;
        }

        public function setChangeFreq( $changefreq ) {
            $this->changefreq = $changefreq;
            return $this;
        }

        public function getPriority() {
            return $this->priority;
        }

        public function setPriority( $priority ) {
            $this->priority = $priority;
            return $this;
        }

        public function hasChild() {
            return $this->child;
        }

        public function setChild($child) {
            $this->child = (boolean)$child;
            return $this;
        }

        public function getChildrenLoc() {
            return $this->childrenLoc;
        }

        public function setChildrenLoc($childrenLoc) {
            $this->childrenLoc = $childrenLoc;
            return $this;
        }

    }

}
