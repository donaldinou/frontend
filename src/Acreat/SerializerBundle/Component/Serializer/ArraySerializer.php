<?php

namespace Acreat\SerializerBundle\Component\Serializer {

    use JMS\Serializer\XmlSerializationVisitor;
    use JMS\Serializer\Context;
    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\iSerializer as SerializerObject;
    use Acreat\SerializerBundle\Component\Factory\Helper;
    use Acreat\SerializerBundle\Component\Helper\SerializerHelper;
    use Acreat\SerializerBundle\Component\Helper\JsonHelper;
    use Acreat\SerializerBundle\Component\Helper\XMLHelper;
    use Acreat\SerializerBundle\Component\Helper\YamlHelper;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class ArraySerializer extends \ArrayObject implements SerializerObject {

        /**
         * @JMS\PreSerialize
         */
        public function PreSerialize() {
            return SerializerHelper::PreSerialize($this);
        }

        /**
         * @JMS\HandlerCallback("xml", direction="serialization")
         */
        public function serializeToXml(XmlSerializationVisitor $visitor, $data, Context $context) {
            // We change the base type, and pass through possible parameters.
            $type['name'] = 'array';

            $visitor->visitArray((array)$this, $type, $context);
        }

        /**
         *
         */
        public function serialize($format) {
            return Helper::serialize($this, $format);
        }

        public function __toString() {
            return $this->serialize();
        }

    }

}
