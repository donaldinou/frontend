<?php

namespace Acreat\SerializerBundle\Component\Serializer {

    use Acreat\SerializerBundle\Component\Helper\XMLHelper;
    use JMS\Serializer\Annotation as JMS;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    abstract class JsonSerializer extends Serializer {

        /**
         * @JMS\PreSerialize
         */
        public function PreSerialize() {
            return JsonHelper::PreSerialize($this);
        }

        /**
         *
         */
        public function serialize($format) {
            return JsonHelper::serialize($this);
        }

    }

}
