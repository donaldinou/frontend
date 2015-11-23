<?php

namespace Acreat\SerializerBundle\Component\Helper {
    

    class YamlHelper extends SerializerHelper {

        /**
         *
         */
        public static function serialize( $object ) {
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            return $serializer->serialize($object, 'yaml');
        }

    }

}
