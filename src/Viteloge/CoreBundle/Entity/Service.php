<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;

    class Service {

        private $name;

        private $image;

        private $icon;

        private $title;

        private $description;

        public function getName() {
            return $this->name;
        }

        public function setName($name) {
            $this->name = $name;
            return $this;
        }

        public function getImage() {
            return $this->image;
        }

        public function setImage($image) {
            $this->image = $image;
            return $this;
        }

        public function getIcon() {
            return $this->icon;
        }

        public function setIcon($icon) {
            $this->icon = $icon;
            return $this;
        }

        public function getTitle() {
            return $this->title;
        }

        public function setTitle($title) {
            $this->title = $title;
            return $this;
        }

        public function getDescription() {
            return $this->description;
        }

        public function setDescription($description) {
            $this->description = $description;
            return $this;
        }

    }

}
