<?php

namespace Acreat\CoreBundle\Component {

    /**
     * Legacy: Used in order to serialize for Trovit, Yakaz...
     */
    class Generic {

        /**
         *
         */
        public function __call( $method, $args ) {
            $accessors = array( 'get', 'set', 'is' );
            $access = substr($method, 0, 3);
            $property = substr($method, 3);
            if (in_array($access, $accessors) && property_exists($this, $property)) {
                switch ($access) {
                    case 'is':
                        return (bool)$this->$property;
                        break;

                    case 'set':
                        return $this->__set($property, $args);
                        break;

                    case 'get':
                    default:
                        return $this->__get($property);
                        break;
                }
            }
        }

        /**
         *
         */
        public function __set( $name, $value ) {
            if (method_exists($this, 'set'.ucfirst($name))) {
                $this->{'set'.ucfirst($name)}($value);
            }
            elseif (property_exists($this, $name)) {
                $this->$name = $value;
            }
            else {
                // none;
            }
            return $this;
        }

        /**
         *
         */
        public function __get( $name ) {
            $result = null;
            if (property_exists($this, $name)) {
                $result = $this->$name;
            }
            return $result;
        }

    }

}
