<?php

namespace Acreat\SerializerBundle\Component\Helper {

    use Acreat\SerializerBundle\Component\Factory\Helper;

    abstract class SerializerHelper {

        /**
         *
         */
        public static function getClassName( $object ) {
            $classname = get_class($object);
            if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
                $classname = $matches[1];
            }
            return $classname;
        }

        /**
         *
         */
        public static function PreSerialize( $class ) {
            return Helper::PreSerialize($class);
        }

        /**
         *
         */
        /*abstract */public static function serialize( $object ) {
            throw new \BadMethodCallException(
                'You must override the serialize() method in the concrete command class.'
            );
        }

    }

}
