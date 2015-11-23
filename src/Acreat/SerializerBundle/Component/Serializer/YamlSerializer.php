<?php

namespace Acreat\SerializerBundle\Component\Serializer {

    use Acreat\SerializerBundle\Component\Helper\XMLHelper;
    use JMS\Serializer\Annotation as JMS;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    abstract class YamlSerializer extends Serializer {

        /**
         * @JMS\PreSerialize
         */
        public function PreSerialize() {
            return YamlHelper::PreSerialize($this);
        }

        /**
         *
         */
        public function serialize($format) {
            return YamlHelper::serialize($this);
        }

    }

}
