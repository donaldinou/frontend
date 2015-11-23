<?php

namespace Acreat\SerializerBundle\Component\Helper {
    

    class XMLHelper extends SerializerHelper {

        /**
         *
         */
        public static function serialize( $object ) {
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            return $serializer->serialize($object, 'xml');
        }

    }

}
