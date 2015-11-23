<?php

namespace Acreat\YakazBundle\Component\Attribute {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;
    use Acreat\SerializerBundle\Component\Annotation\Enum;
    use Acreat\YakazBundle\Enum\Volume;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Surface extends Serializer {

        /**
         * @Required
         * @Enum("Acreat\YakazBundle\Enum\Volume")
         * @JMS\SerializedName("unit")
         * @JMS\XmlAttribute
         */
        protected $unit;

        /**
         * @Required
         * @JMS\XmlValue
         */
        protected $value;

        public function __construct( $value=0 ) {
            $this->unit = Volume::__default;
            $this->value = $value;
        }

    }

}
