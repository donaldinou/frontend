<?php

namespace Acreat\YakazBundle\Component\Element {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Picture extends Serializer {

        /**
         * @Required
         * @JMS\XmlValue
         */
        protected $value;

    }

}
