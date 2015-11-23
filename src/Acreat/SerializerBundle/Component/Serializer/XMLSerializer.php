<?php

namespace Acreat\SerializerBundle\Component\Serializer {

    use Acreat\SerializerBundle\Component\Helper\XMLHelper;
    use JMS\Serializer\Annotation as JMS;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    abstract class XMLSerializer extends Serializer {

        /**
         * @JMS\PreSerialize
         */
        public function PreSerialize() {
            return XMLHelper::PreSerialize($this);
        }

        /**
         *
         */
        public function serialize($format) {
            return XMLHelper::serialize($this);
        }

        /**
         *
         */
        public function __toString() {
            return $this->serialize();
        }

    }

}
