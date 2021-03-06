<?php

namespace Acreat\TrovitBundle\Component {

    use JMS\Serializer\XmlSerializationVisitor;
    use JMS\Serializer\Context;
    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\ArraySerializer;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Pictures extends ArraySerializer {

        /**
         * @JMS\HandlerCallback("xml", direction="serialization")
         */
        public function serializeToXml(XmlSerializationVisitor $visitor, $data, Context $context) {
            return parent::serializeToXml($visitor, $data, $context);
        }

    }

}
