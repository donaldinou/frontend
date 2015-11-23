<?php

namespace Acreat\MitulaBundle\Component\Collection {

    use JMS\Serializer\XmlSerializationVisitor;
    use JMS\Serializer\Context;
    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\ArraySerializer;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Ads extends ArraySerializer {

        /**
         * @JMS\HandlerCallback("xml", direction="serialization")
         */
        public function serializeToXml(XmlSerializationVisitor $visitor, $data, Context $context) {
            return parent::serializeToXml($visitor, $data, $context);
        }

    }

}
