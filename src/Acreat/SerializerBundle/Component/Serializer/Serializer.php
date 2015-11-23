<?php

namespace Acreat\SerializerBundle\Component\Serializer {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\CoreBundle\Component\Generic;
    use Acreat\SerializerBundle\Component\Factory\Helper;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    abstract class Serializer extends Generic implements iSerializer {

        /**
         * @JMS\PreSerialize
         */
        public function PreSerialize() {
            return Helper::PreSerialize($this);
        }

        /**
         *
         */
        public function serialize($format) {
            return Helper::serialize($this, $format);
        }

        /**
         *
         */
        public function __toString() {
            return $this->serialize('json');
        }

    }

}
